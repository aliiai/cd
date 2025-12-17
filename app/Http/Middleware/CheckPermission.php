<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware للتحقق من الصلاحيات
 * 
 * يتحقق من أن المستخدم لديه الصلاحية المطلوبة للوصول إلى الصفحة
 */
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // التحقق من أن المستخدم مسجل دخول
        if (!auth()->check()) {
            abort(403, 'يجب تسجيل الدخول للوصول إلى هذه الصفحة.');
        }

        $user = auth()->user();

        // التحقق من أن الحساب نشط
        if (!$user->is_active) {
            auth()->logout();
            abort(403, 'تم إيقاف حسابك. يرجى التواصل مع الإدارة.');
        }

        // Super Admin لديه جميع الصلاحيات
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // التحقق من أن المستخدم لديه الصلاحية المطلوبة
        if (!$user->can($permission)) {
            abort(403, "غير مصرح لك بالوصول إلى هذه الصفحة. الصلاحية المطلوبة: {$permission}");
        }

        return $next($request);
    }
}
