<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

/**
 * Language Controller
 * 
 * هذا الـ Controller يدير تغيير اللغة في التطبيق
 * يحفظ اللغة المختارة في Session ويعيد التوجيه إلى الصفحة السابقة
 */
class LanguageController extends Controller
{
    /**
     * Switch language
     * 
     * تغيير اللغة وحفظها في Session
     * 
     * @param string $locale - اللغة المطلوبة (ar أو en)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {
        // التحقق من أن اللغة مدعومة
        $supportedLocales = ['ar', 'en'];
        
        if (!in_array($locale, $supportedLocales)) {
            // إذا كانت اللغة غير مدعومة، استخدام اللغة الافتراضية
            $locale = config('app.locale', 'en');
        }
        
        // حفظ اللغة في Session
        Session::put('locale', $locale);
        
        // إرجاع استجابة JSON للـ AJAX requests (Livewire)
        if (request()->expectsJson() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'direction' => $locale === 'ar' ? 'rtl' : 'ltr',
                'message' => __('Language changed successfully')
            ]);
        }
        
        // إعادة التوجيه إلى الصفحة السابقة أو الصفحة الرئيسية
        return Redirect::back();
    }
}

