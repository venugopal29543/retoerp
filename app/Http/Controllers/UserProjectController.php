<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Role;
use App\Models\UserProject;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserProjectController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display user-project assignments
     */
    public function index(Request $request)
    {
        // Check permission
        if (!$this->permissionService->canPerformAction('view', 'users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = UserProject::with(['user', 'project', 'role', 'assignedBy'])
                            ->active();

        // Filter by project if specified
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by user if specified
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by role if specified
        if ($request->has('role')) {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $assignments = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $assignments,
            'available_roles' => Role::getAvailableRoles()
        ]);
    }

    /**
     * Assign user to project with role
     */
    public function assign(Request $request)
    {
        // Check permission
        if (!$this->permissionService->canPerformAction('manage', 'users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'role_id' => 'required|exists:roles,id',
            'permissions_override' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($request->user_id);
        $project = Project::findOrFail($request->project_id);
        $role = Role::findOrFail($request->role_id);

        // Check if user can be assigned to this project
        if (!$this->permissionService->canAccessProject($project->id)) {
            return response()->json(['error' => 'Cannot assign to this project'], 403);
        }

        try {
            $assignment = $user->assignToProject(
                $project->id,
                $role->id,
                auth()->id(),
                $request->permissions_override
            );

            return response()->json([
                'success' => true,
                'message' => "User {$user->name} assigned as {$role->display_name} to {$project->name}",
                'data' => $assignment->load(['user', 'project', 'role'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign user to project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user's role in project
     */
    public function updateRole(Request $request, UserProject $assignment)
    {
        // Check permission
        if (!$this->permissionService->canPerformAction('manage', 'users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permissions_override' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $assignment->update([
                'role_id' => $request->role_id,
                'permissions_override' => $request->permissions_override,
                'assigned_by' => auth()->id(),
                'assigned_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User role updated successfully',
                'data' => $assignment->load(['user', 'project', 'role'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove user from project
     */
    public function remove(Request $request)
    {
        // Check permission
        if (!$this->permissionService->canPerformAction('manage', 'users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($request->user_id);
        $project = Project::findOrFail($request->project_id);

        try {
            $user->removeFromProject($project->id);

            return response()->json([
                'success' => true,
                'message' => "User {$user->name} removed from {$project->name}"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove user from project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get project team summary
     */
    public function getProjectTeam($projectId)
    {
        $project = Project::findOrFail($projectId);

        // Check permission
        if (!$this->permissionService->canAccessProject($project->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $teamSummary = $project->getTeamSummary();

        return response()->json([
            'success' => true,
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'type' => $project->type
            ],
            'team' => $teamSummary
        ]);
    }

    /**
     * Get user's role summary across projects
     */
    public function getUserRoles($userId)
    {
        $user = User::findOrFail($userId);

        // Check permission - users can view their own roles, others need permission
        if ($user->id !== auth()->id() && !$this->permissionService->canPerformAction('view', 'users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $roleSummary = $user->getRoleSummary();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ],
            'roles' => $roleSummary
        ]);
    }

    /**
     * Bulk assign users to project
     */
    public function bulkAssign(Request $request)
    {
        // Check permission
        if (!$this->permissionService->canPerformAction('manage', 'users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'assignments' => 'required|array',
            'assignments.*.user_id' => 'required|exists:users,id',
            'assignments.*.role_id' => 'required|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $project = Project::findOrFail($request->project_id);
        $successCount = 0;
        $failures = [];

        foreach ($request->assignments as $assignment) {
            try {
                $user = User::findOrFail($assignment['user_id']);
                $user->assignToProject(
                    $project->id,
                    $assignment['role_id'],
                    auth()->id()
                );
                $successCount++;
            } catch (\Exception $e) {
                $failures[] = [
                    'user_id' => $assignment['user_id'],
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$successCount} users assigned successfully",
            'failures' => $failures
        ]);
    }

    /**
     * Get available users for assignment to project
     */
    public function getAvailableUsers($projectId)
    {
        $project = Project::findOrFail($projectId);

        // Check permission
        if (!$this->permissionService->canAccessProject($project->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get users in same tenant who are not already assigned to this project
        $assignedUserIds = $project->userProjects()
                                  ->where('is_active', true)
                                  ->pluck('user_id');

        $availableUsers = User::where('tenant_id', $project->tenant_id)
                             ->whereNotIn('id', $assignedUserIds)
                             ->where('is_active', true)
                             ->select(['id', 'name', 'email', 'designation'])
                             ->get();

        return response()->json([
            'success' => true,
            'users' => $availableUsers,
            'roles' => Role::where('tenant_id', $project->tenant_id)
                          ->orWhere('is_system_role', true)
                          ->get(['id', 'name', 'display_name', 'description'])
        ]);
    }
}
