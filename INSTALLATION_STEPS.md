# خطوات التثبيت التفصيلية

## الخطوة 1: تثبيت الحزم الأساسية

```bash
composer install
composer require laravel/jetstream
composer require spatie/laravel-permission
```

## الخطوة 2: تثبيت Jetstream مع Livewire

```bash
php artisan jetstream:install livewire
```

**ملاحظة:** هذا الأمر سينشئ:
- Migrations إضافية لـ Jetstream (sessions, teams, etc.)
- Views للـ authentication
- Config files
- Service Providers

## الخطوة 3: نشر Migrations الخاصة بـ Spatie Permission

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

هذا سينشئ migrations في `database/migrations/`:
- `xxxx_xx_xx_xxxxxx_create_permission_tables.php`

## الخطوة 4: تشغيل جميع Migrations

```bash
php artisan migrate
```

**تحذير:** إذا واجهت خطأ "duplicate column" للأعمدة مثل `two_factor_secret`، فهذا يعني أن Jetstream قد أضافها بالفعل. في هذه الحالة، يمكنك:
1. حذف migration المكررة
2. أو تعديل migration الحالي لإزالة الأعمدة المكررة

## الخطوة 5: تشغيل Seeders

```bash
php artisan db:seed
```

أو بشكل محدد:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

## الخطوة 6: تثبيت NPM Packages

```bash
npm install
```

## الخطوة 7: بناء الأصول

للتطوير:
```bash
npm run dev
```

للإنتاج:
```bash
npm run build
```

## الخطوة 8: تشغيل الخادم

```bash
php artisan serve
```

## اختبار النظام

1. افتح المتصفح على `http://localhost:8000`
2. انتقل إلى `/register` لإنشاء حساب جديد
3. أو استخدم المستخدمين الافتراضيين:
   - Admin: `admin@example.com` / `password`
   - Owner: `owner@example.com` / `password`
4. بعد تسجيل الدخول، سيتم إعادة توجيهك تلقائياً إلى لوحة التحكم الخاصة بدورك

## هيكل المشروع بعد التثبيت

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   └── DashboardController.php
│   │   └── Owner/
│   │       └── DashboardController.php
│   ├── Middleware/
│   │   └── EnsureUserHasRole.php
│   └── Responses/
│       └── LoginResponse.php
├── Models/
│   └── User.php (مع HasRoles trait)
└── Providers/
    └── AppServiceProvider.php (مع LoginResponse binding)

database/
└── seeders/
    └── RolePermissionSeeder.php

resources/
└── views/
    ├── layouts/
    │   ├── app.blade.php
    │   ├── admin.blade.php
    │   └── owner.blade.php
    ├── admin/
    │   └── dashboard.blade.php
    └── owner/
        └── dashboard.blade.php

routes/
└── web.php (مع routes للوحات التحكم)
```

## استكشاف الأخطاء الشائعة

### 1. خطأ: "Class 'Laravel\Fortify\Contracts\LoginResponse' not found"
**الحل:** تأكد من تثبيت Jetstream بشكل صحيح:
```bash
composer require laravel/jetstream
php artisan jetstream:install livewire
```

### 2. خطأ: "Middleware [role] not found"
**الحل:** تأكد من تسجيل Middleware في `bootstrap/app.php`:
```php
$middleware->alias([
    'role' => \App\Http\Middleware\EnsureUserHasRole::class,
]);
```

### 3. خطأ: "Route [admin.dashboard] not defined"
**الحل:** 
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### 4. مشكلة: لا يتم إعادة التوجيه بعد تسجيل الدخول
**الحل:** تأكد من:
1. تسجيل LoginResponse في `AppServiceProvider`
2. أن المستخدم لديه دور (role) معين
3. أن Routes موجودة ومحددة بشكل صحيح

### 5. خطأ: "Table 'permissions' doesn't exist"
**الحل:** تأكد من نشر migrations Spatie وتشغيلها:
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

## ملاحظات إضافية

- بعد تثبيت Jetstream، سيتم إنشاء ملف `config/jetstream.php` - لا تحتاج لتعديله
- Views الخاصة بـ Jetstream ستكون في `resources/views/auth/` - يمكنك تخصيصها
- جميع المسارات محمية بـ Middleware `auth` و `role`
- يمكنك إضافة أدوار وصلاحيات جديدة من خلال Seeder أو مباشرة من الكود

