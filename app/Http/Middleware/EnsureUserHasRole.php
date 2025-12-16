<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // التحقق من أن المستخدم مسجل دخول
        if (!auth()->check()) {
            abort(403, 'You must be logged in to access this page.');
        }

        $user = auth()->user();

        // التحقق من أن الحساب نشط
        if (!$user->is_active) {
            auth()->logout();
            abort(403, 'تم إيقاف حسابك. يرجى التواصل مع الإدارة.');
        }

        // التحقق من أن المستخدم لديه الدور المطلوب
        if (!$user->hasRole($role)) {
            // الحصول على أدوار المستخدم الحالية للمساعدة في التصحيح
            $userRoles = $user->roles->pluck('name')->toArray();
            abort(403, "Unauthorized access. Required role: {$role}. Your roles: " . implode(', ', $userRoles ?: ['none']));
        }

        return $next($request);
    }
}

