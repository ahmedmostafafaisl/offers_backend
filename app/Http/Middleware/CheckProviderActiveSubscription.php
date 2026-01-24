<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscription;

class CheckProviderActiveSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // لازم يكون logged in
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // ✅ لو مش Provider → مش مطلوب subscription
        if ($user->type !== 'provider') {
            return $next($request);
        }

        // ✅ provider لازم يكون عنده subscription فعّالة (is_active + تاريخ صالح)
        $today = now()->toDateString();

        $hasActive = Subscription::where('user_id', $user->id)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('expiration_date', '>=', $today)
            ->exists();

        if (!$hasActive) {
            return response()->json([
                'status' => false,
                'message' => 'Active subscription required.',
            ], 403);
        }

        return $next($request);
    }
}
