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
 * هذا الـ Middleware يقوم بتعيين اللغة الحالية بناءً على Session
 * ويتم استدعاؤه في كل طلب لضمان تطبيق اللغة الصحيحة
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
        // الحصول على اللغة من Session، أو استخدام اللغة الافتراضية
        $locale = Session::get('locale', config('app.locale', 'en'));
        
        // التحقق من أن اللغة مدعومة (ar أو en فقط في هذه المرحلة)
        $supportedLocales = ['ar', 'en'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'en'; // استخدام اللغة الافتراضية إذا كانت اللغة غير مدعومة
        }
        
        // تعيين اللغة للتطبيق
        App::setLocale($locale);
        
        // تمرير الطلب إلى الـ Middleware التالي
        return $next($request);
    }
}

