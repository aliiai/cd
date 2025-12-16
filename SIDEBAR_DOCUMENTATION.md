# Sidebar Component Documentation

## نظرة عامة

تم إنشاء مكون Sidebar ديناميكي باستخدام Livewire و Tailwind CSS. المكون يعرض روابط مختلفة بناءً على دور المستخدم (Admin أو Owner).

## البنية التنظيمية

### الملفات المُنشأة:

1. **config/sidebar.php** - ملف الإعدادات المركزي للروابط
2. **app/Livewire/Sidebar.php** - مكون Livewire
3. **resources/views/livewire/sidebar.blade.php** - View للمكون
4. **resources/views/layouts/admin.blade.php** - Layout محدث مع Sidebar
5. **resources/views/layouts/owner.blade.php** - Layout محدث مع Sidebar

### Controllers و Routes:

- `app/Http/Controllers/Admin/UserController.php`
- `app/Http/Controllers/Admin/SettingsController.php`
- `app/Http/Controllers/Owner/ClientController.php`
- `app/Http/Controllers/Owner/ReportController.php`

## كيفية الاستخدام

### 1. إضافة روابط جديدة

لإضافة رابط جديد، افتح ملف `config/sidebar.php` وأضف الرابط في المصفوفة المناسبة:

```php
'admin' => [
    // ... الروابط الموجودة
    [
        'name' => 'New Page',
        'route' => 'admin.new-page',
        'icon' => 'M12 4v16m8-8H4',
    ],
],
```

### 2. إضافة دور جديد

لإضافة دور جديد (مثل 'manager'):

1. أضف قسم جديد في `config/sidebar.php`:
```php
'manager' => [
    [
        'name' => 'Dashboard',
        'route' => 'manager.dashboard',
        'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    ],
],
```

2. قم بتحديث دالة `getLinksForUser` في `app/Livewire/Sidebar.php`:
```php
if ($user->hasRole('manager')) {
    return $sidebarConfig['manager'] ?? [];
}
```

### 3. تخصيص الأيقونات

الأيقونات تستخدم SVG paths من Heroicons. يمكنك الحصول على أيقونات من:
- https://heroicons.com/

استخدم `d` attribute من SVG كقيمة `icon` في ملف الإعدادات.

### 4. تخصيص التصميم

يمكنك تخصيص التصميم من خلال تعديل `resources/views/livewire/sidebar.blade.php`:

- تغيير الألوان: عدّل classes Tailwind CSS
- تغيير العرض: عدّل `w-64` في div الرئيسي
- إضافة عناصر جديدة: أضف HTML في الأقسام المناسبة

## المميزات

✅ ديناميكي - يعرض روابط مختلفة لكل دور  
✅ مركزي - جميع الروابط في ملف إعدادات واحد  
✅ قابل للتوسعة - سهل إضافة أدوار وروابط جديدة  
✅ تصميم جميل - باستخدام Tailwind CSS  
✅ تفاعلي - يبرز الرابط النشط تلقائياً  
✅ Responsive - يعمل على جميع الأجهزة  

## Routes المتاحة

### Admin Routes:
- `/admin/dashboard` - لوحة التحكم
- `/admin/users` - إدارة المستخدمين
- `/admin/settings` - الإعدادات

### Owner Routes:
- `/owner/dashboard` - لوحة التحكم
- `/owner/clients` - إدارة العملاء
- `/owner/reports` - التقارير

## ملاحظات مهمة

1. تأكد من أن المستخدم لديه دور (role) قبل الوصول للصفحات
2. جميع Routes محمية بـ Middleware `auth` و `role`
3. المكون يستخدم Livewire، تأكد من وجود `@livewireStyles` و `@livewireScripts` في layouts
4. الروابط تتحقق تلقائياً من Route الحالي وتبرز الرابط النشط

## استكشاف الأخطاء

### المشكلة: Sidebar لا يظهر
**الحل:** تأكد من وجود `@livewire('sidebar')` في layout

### المشكلة: الروابط لا تعمل
**الحل:** تأكد من أن Routes موجودة في `routes/web.php`

### المشكلة: لا تظهر أي روابط
**الحل:** تأكد من أن المستخدم لديه دور (admin أو owner) وأن الروابط موجودة في `config/sidebar.php`

