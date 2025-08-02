<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PermissionService;
use Symfony\Component\HttpFoundation\Response;

class MultiTenantMiddleware
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Set tenant context
        $tenant = $this->permissionService->getCurrentTenant();
        
        if (!$tenant) {
            return redirect()->route('tenant.select')
                           ->with('error', 'Please select a valid tenant.');
        }

        // Check tenant status
        if ($tenant->status !== 'active') {
            return response()->view('errors.tenant-inactive', compact('tenant'), 403);
        }

        // Check subscription
        if ($tenant->expires_at && $tenant->expires_at->isPast()) {
            return response()->view('errors.subscription-expired', compact('tenant'), 402);
        }

        // Share tenant context with views
        view()->share('tenant', $tenant);
        view()->share('permissionService', $this->permissionService);
        
        // Add tenant to request
        $request->merge(['tenant' => $tenant]);

        return $next($request);
    }
}
