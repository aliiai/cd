# نظام تبديل اللغة (Language Switcher)

## نظرة عامة

تم إنشاء نظام كامل لتبديل اللغة بين العربية والإنجليزية مع دعم RTL/LTR تلقائياً. النظام يستخدم Livewire و Alpine.js لتحديث الصفحة بدون إعادة تحميل كاملة.

## الملفات المضافة/المحدثة

### 1. ملفات الترجمة
- `lang/ar.json` - ترجمات عربية
- `lang/en.json` - ترجمات إنجليزية

### 2. Middleware
- `app/Http/Middleware/SetLocale.php` - يعين اللغة الحالية من Session في كل طلب

### 3. Controller
- `app/Http/Controllers/LanguageController.php` - يدير تغيير اللغة عبر Route

### 4. Livewire Component
- `app/Livewire/LanguageSwitcher.php` - مكون Livewire لتبديل اللغة
- `resources/views/livewire/language-switcher.blade.php` - واجهة المكون

### 5. Routes
- تم إضافة Route: `/language/{locale}` في `routes/web.php`

### 6. Layouts
- `resources/views/layouts/admin.blade.php` - محدث لدعم RTL/LTR
- `resources/views/layouts/owner.blade.php` - محدث لدعم RTL/LTR

### 7. Components
- `resources/views/components/header.blade.php` - محدث لاستخدام Language Switcher

### 8. CSS
- `resources/css/app.css` - إضافة دعم RTL

## كيفية الاستخدام

### في Blade Views

استخدم دالة `__()` للترجمة:

```blade
{{ __('Dashboard') }}
{{ __('Settings') }}
{{ __('Logout') }}
```

### إضافة ترجمات جديدة

1. افتح `lang/ar.json` وأضف الترجمة العربية:
```json
{
    "New Text": "نص جديد"
}
```

2. افتح `lang/en.json` وأضف الترجمة الإنجليزية:
```json
{
    "New Text": "New Text"
}
```

### استخدام الترجمة في الكود

```php
// في PHP
__('Dashboard')

// في Blade
{{ __('Dashboard') }}
```

## المميزات

1. ✅ تبديل اللغة بنقرة واحدة
2. ✅ حفظ اللغة في Session
3. ✅ تغيير RTL/LTR تلقائياً
4. ✅ تحديث جميع النصوص تلقائياً
5. ✅ دعم خطوط عربية (Cairo)
6. ✅ قابل للتوسعة لإضافة لغات جديدة

## إضافة لغة جديدة

1. أضف اللغة إلى `$supportedLocales` في:
   - `app/Http/Middleware/SetLocale.php`
   - `app/Livewire/LanguageSwitcher.php`
   - `app/Http/Controllers/LanguageController.php`

2. أنشئ ملف ترجمة جديد: `lang/{locale}.json`

3. أضف الخطوط المناسبة في Layouts إذا لزم الأمر

## ملاحظات

- اللغة تُحفظ في Session وتستمر عبر الصفحات
- الاتجاه (RTL/LTR) يتغير تلقائياً بناءً على اللغة
- جميع النصوص في Header و Sidebar تدعم الترجمة
- يمكن إضافة المزيد من النصوص بسهولة في ملفات JSON

