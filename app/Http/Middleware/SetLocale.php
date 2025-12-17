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
        $locale = Session::get('locale');
        
        // إذا لم تكن اللغة موجودة في Session، استخدام اللغة الافتراضية
        if (!$locale) {
            $locale = config('app.locale', 'en');
        }
        
        // التحقق من أن اللغة مدعومة (ar أو en فقط في هذه المرحلة)
        $supportedLocales = ['ar', 'en'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.locale', 'en'); // استخدام اللغة الافتراضية إذا كانت اللغة غير مدعومة
        }
        
        // تعيين اللغة للتطبيق - يجب أن يكون قبل أي استخدام لـ __()
        App::setLocale($locale);
        
        // التأكد من أن اللغة تم تعيينها بشكل صحيح في config
        config(['app.locale' => $locale]);
        
        // تمرير الطلب إلى الـ Middleware التالي
        $response = $next($request);
        
        // التأكد من أن اللغة محفوظة في Session
        if (!Session::has('locale') || Session::get('locale') !== $locale) {
            Session::put('locale', $locale);
        }
        
        return $response;
    }
}

