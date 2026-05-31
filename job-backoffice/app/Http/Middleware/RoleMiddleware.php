<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Bu middleware kullanıcının rolünü kontrol eder
     * Kullanım: Route::middleware(['auth', 'role:admin,company-owner'])
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles - İzin verilen roller (admin, company-owner, job-seeker)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $role = Auth::user()->role;
            $hasAccess = in_array($role, $roles);

            if (!$hasAccess) {
                abort(403);
            }

            // User has access, continue
            return $next($request);
        }

        // User not authenticated, redirect to login
        return redirect()->route('login');
    }
}
