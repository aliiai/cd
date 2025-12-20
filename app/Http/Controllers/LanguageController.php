<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

/**
 * Language Controller
 * 
 * هذا الـ Controller يقوم بمعالجة طلبات تغيير اللغة
 */
class LanguageController extends Controller
{
    /**
     * تغيير اللغة
     * 
     * @param string $locale اللغة المطلوبة (ar أو en)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {
        // التحقق من أن اللغة المدخلة صحيحة
        $allowedLocales = ['ar', 'en'];
        
        if (!in_array($locale, $allowedLocales)) {
            // إذا كانت اللغة غير صحيحة، نستخدم اللغة الافتراضية
            $locale = config('app.locale', 'ar');
        }
        
        // حفظ اللغة في الـ Session
        Session::put('locale', $locale);
        
        // الرجوع للصفحة السابقة
        return Redirect::back();
    }
}
