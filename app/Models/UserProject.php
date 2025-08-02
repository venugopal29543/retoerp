<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'role_id',
        'is_active',
        'assigned_at',
        'assigned_by',
        'permissions_override',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'assigned_at' => 'datetime',
        'permissions_override' => 'array'
    ];

    /**
     * Get the user for this project assignment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project for this assignment
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the role for this assignment
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the user who assigned this role
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Check if user has specific permission in this project
     */
    public function hasPermission($permission)
    {
        // Check role permissions first
        if ($this->role && $this->role->hasPermission($permission)) {
            return true;
        }

        // Check override permissions
        if ($this->permissions_override && in_array($permission, $this->permissions_override)) {
            return true;
        }

        return false;
    }

    /**
     * Get all effective permissions for this user-project assignment
     */
    public function getEffectivePermissions()
    {
        $rolePermissions = $this->role ? $this->role->getAllPermissions() : collect();
        $overridePermissions = collect($this->permissions_override ?? []);
        
        return $rolePermissions->merge($overridePermissions)->unique();
    }

    /**
     * Scope for active assignments only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific project
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for specific role
     */
    public function scopeWithRole($query, $roleName)
    {
        return $query->whereHas('role', function($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }
}
