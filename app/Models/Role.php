<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
        'is_system_role',
        'tenant_id'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_system_role' => 'boolean'
    ];

    /**
     * Define available roles for real estate projects
     */
    public static function getAvailableRoles()
    {
        return [
            'super_admin' => [
                'display_name' => 'Super Administrator',
                'description' => 'Full system access across all tenants',
                'permissions' => ['*'],
                'is_system_role' => true
            ],
            'tenant_admin' => [
                'display_name' => 'Tenant Administrator', 
                'description' => 'Full access within tenant organization',
                'permissions' => [
                    'tenant.manage',
                    'projects.manage',
                    'users.manage',
                    'roles.manage',
                    'properties.manage',
                    'bookings.manage',
                    'reports.view',
                    'settings.manage'
                ],
                'is_system_role' => false
            ],
            'project_manager' => [
                'display_name' => 'Project Manager',
                'description' => 'Manages specific projects and their properties',
                'permissions' => [
                    'projects.view',
                    'projects.edit',
                    'properties.manage',
                    'bookings.manage',
                    'agents.manage',
                    'reports.view',
                    'customers.manage'
                ],
                'is_system_role' => false
            ],
            'sales_manager' => [
                'display_name' => 'Sales Manager',
                'description' => 'Oversees sales operations and agent performance',
                'permissions' => [
                    'sales.manage',
                    'agents.supervise',
                    'bookings.approve',
                    'customers.manage',
                    'reports.sales',
                    'commissions.manage',
                    'targets.set'
                ],
                'is_system_role' => false
            ],
            'agent' => [
                'display_name' => 'Sales Agent',
                'description' => 'Handles customer interactions and property sales',
                'permissions' => [
                    'properties.view',
                    'bookings.create',
                    'bookings.edit_own',
                    'customers.create',
                    'customers.edit_own',
                    'leads.manage',
                    'commissions.view_own'
                ],
                'is_system_role' => false
            ],
            'marketing_executive' => [
                'display_name' => 'Marketing Executive',
                'description' => 'Manages marketing campaigns and lead generation',
                'permissions' => [
                    'marketing.manage',
                    'leads.create',
                    'campaigns.manage',
                    'content.manage',
                    'analytics.view'
                ],
                'is_system_role' => false
            ],
            'finance_manager' => [
                'display_name' => 'Finance Manager',
                'description' => 'Handles financial operations and payment processing',
                'permissions' => [
                    'payments.manage',
                    'invoices.manage',
                    'financial_reports.view',
                    'commissions.calculate',
                    'accounts.manage'
                ],
                'is_system_role' => false
            ],
            'customer_support' => [
                'display_name' => 'Customer Support',
                'description' => 'Assists customers with queries and support',
                'permissions' => [
                    'customers.view',
                    'support.manage',
                    'communications.send',
                    'bookings.view'
                ],
                'is_system_role' => false
            ],
            'site_supervisor' => [
                'display_name' => 'Site Supervisor',
                'description' => 'Oversees site operations and property handovers',
                'permissions' => [
                    'site.manage',
                    'handovers.manage',
                    'maintenance.create',
                    'inventory.manage'
                ],
                'is_system_role' => false
            ],
            'viewer' => [
                'display_name' => 'Viewer',
                'description' => 'Read-only access to assigned projects',
                'permissions' => [
                    'projects.view',
                    'properties.view',
                    'bookings.view',
                    'reports.view_basic'
                ],
                'is_system_role' => false
            ]
        ];
    }

    /**
     * Get tenant that owns this role
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Users who have this role
     */
    public function userProjects()
    {
        return $this->hasMany(UserProject::class);
    }

    /**
     * Check if role has specific permission
     */
    public function hasPermission($permission)
    {
        if (in_array('*', $this->permissions)) {
            return true;
        }
        
        return in_array($permission, $this->permissions);
    }

    /**
     * Get all permissions for this role
     */
    public function getAllPermissions()
    {
        if (in_array('*', $this->permissions)) {
            return collect(self::getAllAvailablePermissions());
        }
        
        return collect($this->permissions);
    }

    /**
     * Get all available permissions in the system
     */
    public static function getAllAvailablePermissions()
    {
        return [
            // Tenant Management
            'tenant.manage',
            
            // Project Management
            'projects.view',
            'projects.create',
            'projects.edit',
            'projects.delete',
            'projects.manage',
            
            // Property Management
            'properties.view',
            'properties.create',
            'properties.edit',
            'properties.delete',
            'properties.manage',
            
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.manage',
            
            // Role Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'roles.manage',
            
            // Booking Management
            'bookings.view',
            'bookings.create',
            'bookings.edit',
            'bookings.edit_own',
            'bookings.delete',
            'bookings.approve',
            'bookings.manage',
            
            // Customer Management
            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.edit_own',
            'customers.delete',
            'customers.manage',
            
            // Sales Management
            'sales.view',
            'sales.manage',
            'agents.supervise',
            'targets.set',
            'targets.view',
            
            // Marketing
            'marketing.manage',
            'campaigns.manage',
            'leads.create',
            'leads.manage',
            'content.manage',
            
            // Finance
            'payments.view',
            'payments.manage',
            'invoices.manage',
            'commissions.view_own',
            'commissions.view_all',
            'commissions.manage',
            'commissions.calculate',
            'accounts.manage',
            
            // Reports
            'reports.view',
            'reports.view_basic',
            'reports.sales',
            'financial_reports.view',
            'analytics.view',
            
            // Support
            'support.manage',
            'communications.send',
            
            // Site Management
            'site.manage',
            'handovers.manage',
            'maintenance.create',
            'maintenance.manage',
            'inventory.manage',
            
            // Settings
            'settings.view',
            'settings.manage'
        ];
    }
}
