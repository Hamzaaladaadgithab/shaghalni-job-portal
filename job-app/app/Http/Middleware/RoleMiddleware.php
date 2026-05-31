<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Bu middleware kullanıcının rolünü kontrol eder
     * job-app uygulamasında sadece job-seeker rolüne sahip kullanıcılar erişebilir
     * Kullanım: Route::middleware(['auth', 'role:job-seeker'])
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles - İzin verilen roller (admin, company-owner, job-seeker)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->role;
        $hasAccess = \in_array($userRole, $roles);

        if (!$hasAccess) {
            // Log the role mismatch for debugging
            Log::warning('Role access denied in job-app', [
                'user_id' => $user->id,
                'user_role' => $userRole,
                'required_roles' => $roles,
                'route' => $request->path(),
                'app' => 'job-app'
            ]);

            // If user is admin, allow access but redirect to appropriate page
            if ($userRole === 'admin') {
                return redirect()->away('http://127.0.0.1:8001/dashboard')
                    ->with('info', 'Admin kullanıcısı olarak backoffice\'e yönlendirildiniz.');
            }

            // Logout the user and redirect to login with message
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Bu uygulamaya sadece iş arayanlar erişebilir. Lütfen job-seeker hesabınızla giriş yapın.');
        }

        // User has access, continue
        return $next($request);
    }
}
