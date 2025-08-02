<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'employee_id',
        'department',
        'designation',
        'tenant_id',
        'is_active',
        'profile_picture',
        'joining_date',
        'emergency_contact'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'joining_date' => 'date',
            'emergency_contact' => 'array'
        ];
    }

    /**
     * Get the tenant this user belongs to
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get all project assignments for this user
     */
    public function projectAssignments()
    {
        return $this->hasMany(UserProject::class);
    }

    /**
     * Get active project assignments only
     */
    public function activeProjectAssignments()
    {
        return $this->hasMany(UserProject::class)->active();
    }

    /**
     * Get all projects this user is assigned to
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_projects')
                    ->withPivot(['role_id', 'is_active', 'assigned_at', 'permissions_override'])
                    ->wherePivot('is_active', true);
    }

    /**
     * Get user's role in a specific project
     */
    public function getRoleInProject($projectId)
    {
        $assignment = $this->projectAssignments()
                          ->where('project_id', $projectId)
                          ->where('is_active', true)
                          ->with('role')
                          ->first();
        
        return $assignment ? $assignment->role : null;
    }

    /**
     * Check if user has specific permission in a project
     */
    public function hasPermissionInProject($permission, $projectId)
    {
        $assignment = $this->projectAssignments()
                          ->where('project_id', $projectId)
                          ->where('is_active', true)
                          ->with('role')
                          ->first();
        
        if (!$assignment) {
            return false;
        }

        return $assignment->hasPermission($permission);
    }

    /**
     * Get all permissions for user in a specific project
     */
    public function getPermissionsInProject($projectId)
    {
        $assignment = $this->projectAssignments()
                          ->where('project_id', $projectId)
                          ->where('is_active', true)
                          ->with('role')
                          ->first();
        
        if (!$assignment) {
            return collect();
        }

        return $assignment->getEffectivePermissions();
    }

    /**
     * Check if user has any role in a project
     */
    public function hasAccessToProject($projectId)
    {
        return $this->projectAssignments()
                   ->where('project_id', $projectId)
                   ->where('is_active', true)
                   ->exists();
    }

    /**
     * Get all projects where user has a specific role
     */
    public function getProjectsWithRole($roleName)
    {
        return $this->projects()
                   ->whereHas('userProjects', function($query) use ($roleName) {
                       $query->where('user_id', $this->id)
                             ->whereHas('role', function($q) use ($roleName) {
                                 $q->where('name', $roleName);
                             });
                   });
    }

    /**
     * Assign user to a project with specific role
     */
    public function assignToProject($projectId, $roleId, $assignedBy = null, $permissions = null)
    {
        // Deactivate any existing assignment for this project
        $this->projectAssignments()
             ->where('project_id', $projectId)
             ->update(['is_active' => false]);

        // Create new assignment
        return $this->projectAssignments()->create([
            'project_id' => $projectId,
            'role_id' => $roleId,
            'is_active' => true,
            'assigned_at' => now(),
            'assigned_by' => $assignedBy,
            'permissions_override' => $permissions
        ]);
    }

    /**
     * Remove user from a project
     */
    public function removeFromProject($projectId)
    {
        return $this->projectAssignments()
                   ->where('project_id', $projectId)
                   ->update(['is_active' => false]);
    }

    /**
     * Get user's role summary across all projects
     */
    public function getRoleSummary()
    {
        return $this->activeProjectAssignments()
                   ->with(['project', 'role'])
                   ->get()
                   ->map(function($assignment) {
                       return [
                           'project' => $assignment->project->name,
                           'project_id' => $assignment->project->id,
                           'role' => $assignment->role->display_name,
                           'role_name' => $assignment->role->name,
                           'assigned_at' => $assignment->assigned_at,
                           'permissions_count' => $assignment->getEffectivePermissions()->count()
                       ];
                   });
    }

    /**
     * Check if user is super admin (has system-level access)
     */
    public function isSuperAdmin()
    {
        return $this->projectAssignments()
                   ->whereHas('role', function($query) {
                       $query->where('name', 'super_admin')
                             ->where('is_system_role', true);
                   })
                   ->where('is_active', true)
                   ->exists();
    }

    /**
     * Check if user is tenant admin
     */
    public function isTenantAdmin()
    {
        return $this->projectAssignments()
                   ->whereHas('role', function($query) {
                       $query->where('name', 'tenant_admin');
                   })
                   ->where('is_active', true)
                   ->exists();
    }
}
