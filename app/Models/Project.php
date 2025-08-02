<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'description',
        'location',
        'settings',
        'features_config',
        'status'
    ];

    protected $casts = [
        'settings' => 'array',
        'features_config' => 'array'
    ];

    // Project types
    const TYPE_VENTURE = 'venture';
    const TYPE_APARTMENT = 'apartment';
    const TYPE_FARMLAND = 'farmland';
    const TYPE_VILLA = 'villa';
    const TYPE_COMMERCIAL = 'commercial';

    public static function getTypes()
    {
        return [
            self::TYPE_VENTURE => 'Venture/Layout',
            self::TYPE_APARTMENT => 'Apartment Complex',
            self::TYPE_FARMLAND => 'Farm Land',
            self::TYPE_VILLA => 'Villa Project',
            self::TYPE_COMMERCIAL => 'Commercial Space'
        ];
    }

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Users assigned to this project
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_projects')
                    ->withPivot(['role_id', 'is_active', 'assigned_at', 'permissions_override'])
                    ->wherePivot('is_active', true);
    }

    /**
     * User project assignments
     */
    public function userProjects()
    {
        return $this->hasMany(UserProject::class);
    }

    /**
     * Active user assignments only
     */
    public function activeUsers()
    {
        return $this->hasMany(UserProject::class)->active()->with(['user', 'role']);
    }

    /**
     * Get users with specific role in this project
     */
    public function getUsersWithRole($roleName)
    {
        return $this->users()
                   ->whereHas('projectAssignments', function($query) use ($roleName) {
                       $query->where('project_id', $this->id)
                             ->whereHas('role', function($q) use ($roleName) {
                                 $q->where('name', $roleName);
                             });
                   });
    }

    /**
     * Get project managers
     */
    public function getProjectManagers()
    {
        return $this->getUsersWithRole('project_manager');
    }

    /**
     * Get sales managers  
     */
    public function getSalesManagers()
    {
        return $this->getUsersWithRole('sales_manager');
    }

    /**
     * Get sales agents
     */
    public function getAgents()
    {
        return $this->getUsersWithRole('agent');
    }

    /**
     * Assign user to project with role
     */
    public function assignUser($userId, $roleId, $assignedBy = null)
    {
        $user = User::find($userId);
        if ($user) {
            return $user->assignToProject($this->id, $roleId, $assignedBy);
        }
        return false;
    }

    /**
     * Remove user from project
     */
    public function removeUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            return $user->removeFromProject($this->id);
        }
        return false;
    }

    /**
     * Get team summary for this project
     */
    public function getTeamSummary()
    {
        return $this->activeUsers()
                   ->get()
                   ->groupBy('role.name')
                   ->map(function($assignments, $roleName) {
                       return [
                           'role' => $assignments->first()->role->display_name,
                           'count' => $assignments->count(),
                           'users' => $assignments->map(function($assignment) {
                               return [
                                   'id' => $assignment->user->id,
                                   'name' => $assignment->user->name,
                                   'email' => $assignment->user->email,
                                   'assigned_at' => $assignment->assigned_at
                               ];
                           })
                       ];
                   });
    }

    // Feature Management at Project Level
    public function hasFeature($featureName)
    {
        // First check if tenant has the feature
        if (!$this->tenant->hasFeature($featureName)) {
            return false;
        }

        // Then check project-specific override
        $projectFeatures = $this->features_config ?? [];
        
        if (isset($projectFeatures[$featureName])) {
            return $projectFeatures[$featureName]['enabled'] ?? false;
        }

        // Default to tenant setting
        return true;
    }

    public function enableFeature($featureName, $settings = [])
    {
        $features = $this->features_config ?? [];
        $features[$featureName] = [
            'enabled' => true,
            'settings' => $settings
        ];
        
        $this->update(['features_config' => $features]);
    }

    public function disableFeature($featureName)
    {
        $features = $this->features_config ?? [];
        $features[$featureName] = [
            'enabled' => false,
            'settings' => []
        ];
        
        $this->update(['features_config' => $features]);
    }

    public function getFeatureSettings($featureName)
    {
        $projectFeatures = $this->features_config ?? [];
        
        if (isset($projectFeatures[$featureName]['settings'])) {
            return $projectFeatures[$featureName]['settings'];
        }

        // Fallback to tenant settings
        return $this->tenant->getFeatureSettings($featureName);
    }
}
