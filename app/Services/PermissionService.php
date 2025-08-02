<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    protected $tenant;
    protected $project;
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->tenant = $this->getCurrentTenant();
        $this->project = $this->getCurrentProject();
    }

    public function getCurrentTenant()
    {
        // Get tenant from subdomain, session, or user context
        $subdomain = $this->getSubdomain();
        
        if ($subdomain) {
            return Cache::remember("tenant_{$subdomain}", 3600, function() use ($subdomain) {
                return Tenant::where('subdomain', $subdomain)
                           ->where('status', 'active')
                           ->first();
            });
        }
        
        return null;
    }

    public function getCurrentProject()
    {
        $projectId = session('current_project_id') ?? request()->get('project_id');
        
        if ($projectId && $this->tenant) {
            return $this->tenant->projects()->find($projectId);
        }
        
        return null;
    }

    public function hasFeature($featureName, $level = 'project')
    {
        switch ($level) {
            case 'tenant':
                return $this->tenant ? $this->tenant->hasFeature($featureName) : false;
            
            case 'project':
                return $this->project ? $this->project->hasFeature($featureName) : false;
            
            default:
                return false;
        }
    }

    public function canPerformAction($action, $resource = null)
    {
        if (!$this->user || !$this->tenant) {
            return false;
        }

        // Get user's role in current tenant
        $userRole = $this->getUserRole();
        
        if (!$userRole) {
            return false;
        }

        // Check specific permission
        return $this->checkPermission($userRole, $action, $resource);
    }

    public function getFeatureConfig($featureName, $level = 'project')
    {
        switch ($level) {
            case 'tenant':
                return $this->tenant ? $this->tenant->getFeatureSettings($featureName) : [];
            
            case 'project':
                return $this->project ? $this->project->getFeatureSettings($featureName) : [];
            
            default:
                return [];
        }
    }

    public function getAvailableFeatures()
    {
        if (!$this->tenant) {
            return [];
        }

        $allFeatures = Tenant::getAvailableFeatures();
        $availableFeatures = [];

        foreach ($allFeatures as $key => $feature) {
            if ($this->hasFeature($key, 'tenant')) {
                $feature['enabled_at_project'] = $this->hasFeature($key, 'project');
                $feature['settings'] = $this->getFeatureConfig($key);
                $availableFeatures[$key] = $feature;
            }
        }

        return $availableFeatures;
    }

    public function getFieldPermissions($formName, $fieldName)
    {
        $userRole = $this->getUserRole();
        
        if (!$userRole) {
            return ['read' => false, 'write' => false];
        }

        // Check field-level permissions
        $permissions = $userRole->permissions ?? [];
        $fieldKey = "{$formName}.{$fieldName}";
        
        return $permissions['fields'][$fieldKey] ?? ['read' => true, 'write' => true];
    }

    public function filterFormFields($formName, $fields)
    {
        $filteredFields = [];
        
        foreach ($fields as $fieldName => $fieldConfig) {
            $permissions = $this->getFieldPermissions($formName, $fieldName);
            
            if ($permissions['read']) {
                $fieldConfig['readonly'] = !$permissions['write'];
                $filteredFields[$fieldName] = $fieldConfig;
            }
        }
        
        return $filteredFields;
    }

    public function getUIConfig()
    {
        return [
            'features' => $this->getAvailableFeatures(),
            'tenant' => $this->tenant ? [
                'name' => $this->tenant->name,
                'domain' => $this->tenant->domain,
                'branding' => $this->tenant->settings['branding'] ?? []
            ] : null,
            'project' => $this->project ? [
                'name' => $this->project->name,
                'type' => $this->project->type,
                'settings' => $this->project->settings
            ] : null,
            'user' => $this->user ? [
                'role' => $this->getUserRole()?->name,
                'permissions' => $this->getUserPermissions()
            ] : null
        ];
    }

    private function getSubdomain()
    {
        $host = request()->getHost();
        $parts = explode('.', $host);
        
        if (count($parts) > 2) {
            return $parts[0];
        }
        
        return null;
    }

    private function getUserRole()
    {
        if (!$this->user || !$this->project) {
            return null;
        }

        // Get user's role in current project
        return $this->user->getRoleInProject($this->project->id);
    }

    private function getUserPermissions()
    {
        if (!$this->user || !$this->project) {
            return [];
        }

        return $this->user->getPermissionsInProject($this->project->id)->toArray();
    }

    private function checkPermission($role, $action, $resource)
    {
        if (!$role) {
            return false;
        }
        
        // Check if role has wildcard permission
        if ($role->hasPermission('*')) {
            return true;
        }
        
        // Check specific action permission
        $actionKey = $resource ? "{$resource}.{$action}" : $action;
        
        return $role->hasPermission($actionKey);
    }

    /**
     * Check if current user can edit specific field based on role permissions
     */
    public function canEditField($context, $fieldName)
    {
        $user = auth()->user();
        $project = $this->getCurrentProject();
        
        if (!$user || !$project) {
            return false;
        }
        
        // Get user's role in current project
        $role = $user->getRoleInProject($project->id);
        
        if (!$role) {
            return false;
        }
        
        // Define field permissions based on context and role
        $fieldPermissions = $this->getProjectFieldPermissions($context, $role->name);
        
        return in_array($fieldName, $fieldPermissions);
    }

    /**
     * Get field permissions for a specific role and context in project
     */
    private function getProjectFieldPermissions($context, $roleName)
    {
        $permissions = [
            'booking' => [
                'super_admin' => ['customer_name', 'customer_phone', 'customer_email', 'customer_address', 'plot_price', 'discount'],
                'tenant_admin' => ['customer_name', 'customer_phone', 'customer_email', 'customer_address', 'plot_price', 'discount'],
                'project_manager' => ['customer_name', 'customer_phone', 'customer_email', 'customer_address', 'plot_price', 'discount'],
                'sales_manager' => ['customer_name', 'customer_phone', 'customer_email', 'customer_address', 'plot_price'],
                'agent' => ['customer_name', 'customer_phone', 'customer_email', 'customer_address'],
                'marketing_executive' => ['customer_name', 'customer_phone', 'customer_email'],
                'customer_support' => ['customer_name', 'customer_phone', 'customer_email'],
                'viewer' => []
            ],
            'property' => [
                'super_admin' => ['name', 'description', 'price', 'size', 'status', 'features'],
                'tenant_admin' => ['name', 'description', 'price', 'size', 'status', 'features'],
                'project_manager' => ['name', 'description', 'price', 'size', 'status', 'features'],
                'sales_manager' => ['name', 'description', 'price', 'size', 'features'],
                'agent' => ['description', 'features'],
                'site_supervisor' => ['status', 'features'],
                'viewer' => []
            ]
        ];
        
        return $permissions[$context][$roleName] ?? [];
    }

    /**
     * Check if current user can access specific project
     */
    public function canAccessProject($projectId)
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Super admin can access all projects
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Tenant admin can access all projects in their tenant
        if ($user->isTenantAdmin()) {
            $project = Project::find($projectId);
            return $project && $project->tenant_id === $user->tenant_id;
        }
        
        // Check specific project access
        return $user->hasAccessToProject($projectId);
    }

    /**
     * Get projects accessible to current user
     */
    public function getAccessibleProjects()
    {
        $user = auth()->user();
        
        if (!$user) {
            return collect();
        }
        
        // Super admin can access all projects
        if ($user->isSuperAdmin()) {
            return Project::all();
        }
        
        // Tenant admin can access all projects in their tenant
        if ($user->isTenantAdmin()) {
            return Project::where('tenant_id', $user->tenant_id)->get();
        }
        
        // Return user's assigned projects
        return $user->projects;
    }

    /**
     * Get current user's role summary across all projects
     */
    public function getUserRoleSummary()
    {
        $user = auth()->user();
        
        if (!$user) {
            return collect();
        }
        
        return $user->getRoleSummary();
    }
}
