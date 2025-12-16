<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

/**
 * Language Switcher Component
 * 
 * هذا المكون Livewire يدير تبديل اللغة في Header
 * يقوم بتغيير اللغة وحفظها في Session وتحديث الصفحة بدون إعادة تحميل كاملة
 */
class LanguageSwitcher extends Component
{
    /**
     * اللغة الحالية
     */
    public $currentLocale;

    /**
     * اتجاه النص (rtl أو ltr)
     */
    public $direction;

    /**
     * Mount the component
     * 
     * تهيئة المكون عند تحميله
     */
    public function mount()
    {
        // الحصول على اللغة الحالية من Session
        $this->currentLocale = Session::get('locale', config('app.locale', 'en'));
        
        // تحديد الاتجاه بناءً على اللغة
        $this->direction = $this->currentLocale === 'ar' ? 'rtl' : 'ltr';
    }

    /**
     * Switch language
     * 
     * تغيير اللغة وحفظها في Session
     * 
     * @param string $locale - اللغة المطلوبة (ar أو en)
     */
    public function switchLanguage($locale)
    {
        // التحقق من أن اللغة مدعومة
        $supportedLocales = ['ar', 'en'];
        
        if (!in_array($locale, $supportedLocales)) {
            return; // إذا كانت اللغة غير مدعومة، لا نفعل شيء
        }
        
        // حفظ اللغة في Session
        Session::put('locale', $locale);
        
        // تحديث اللغة الحالية والاتجاه
        $this->currentLocale = $locale;
        $this->direction = $locale === 'ar' ? 'rtl' : 'ltr';
        
        // تعيين اللغة للتطبيق
        App::setLocale($locale);
        
        // إرسال event لتحديث الصفحة
        $this->dispatch('language-changed', locale: $locale, direction: $this->direction);
        
        // إعادة تحميل الصفحة لتطبيق جميع التغييرات (الترجمة، الاتجاه، إلخ)
        $this->redirect(request()->header('Referer') ?? url()->previous(), navigate: false);
    }

    /**
     * Render the component
     * 
     * عرض المكون
     */
    public function render()
    {
        return view('livewire.language-switcher');
    }
}

