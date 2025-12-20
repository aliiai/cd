<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

/**
 * SetLocale Middleware
 * 
 * هذا الـ Middleware يقوم بتعيين لغة التطبيق بناءً على اللغة المحفوظة في الـ Session.
 * إذا لم تكن هناك لغة محفوظة، يستخدم اللغة الافتراضية من config/app.php
 */
class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // الحصول على اللغة من الـ Session
        $locale = Session::get('locale', config('app.locale', 'ar'));
        
        // التحقق من أن اللغة المدخلة صحيحة (ar أو en)
        $allowedLocales = ['ar', 'en'];
        if (!in_array($locale, $allowedLocales)) {
            $locale = config('app.locale', 'ar');
        }
        
        // تعيين لغة التطبيق
        App::setLocale($locale);
        
        return $next($request);
    }
}
