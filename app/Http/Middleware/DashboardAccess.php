<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DashboardAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // لازم يكون مسجل دخول
        if (!$user) {
            return redirect()->route('login');
        }

        // ✅ الداش فقط للـ employee
        if ($user->type !== 'employee') {
            abort(403, 'Dashboard access denied.');
        }

        if (!$user->can('dashboard.view')) {
            abort(403, 'No dashboard permission.');
        }


        // ✅ لازم يكون عنده Role (أو super_admin)
        // لو انت بتستخدم spatie roles:
        if (method_exists($user, 'hasAnyRole')) {
            if (!$user->hasAnyRole(['super_admin', 'admin', 'manager', 'viewer'])) {
                abort(403, 'No dashboard role assigned.');
            }
        }

        return $next($request);
    }
}
