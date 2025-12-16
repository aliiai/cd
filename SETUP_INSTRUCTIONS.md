# تعليمات إعداد نظام تسجيل متعدد الأدوار - Laravel 12

## المتطلبات الأساسية

- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (أو أي قاعدة بيانات أخرى)

## خطوات التثبيت

### 1. تثبيت الحزم المطلوبة

```bash
composer install
composer require laravel/jetstream
composer require spatie/laravel-permission
```

### 2. تثبيت Jetstream (Stack Livewire)

```bash
php artisan jetstream:install livewire
```

### 3. تثبيت NPM Packages

```bash
npm install
```

### 4. إعداد قاعدة البيانات

تأكد من أن ملف `.env` يحتوي على إعدادات قاعدة البيانات الصحيحة:

```env
DB_CONNECTION=sqlite
# أو
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. تشغيل Migrations

```bash
php artisan migrate
```

### 6. نشر Migrations الخاصة بـ Spatie Permission

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### 7. تشغيل Seeders

```bash
php artisan db:seed
```

أو بشكل محدد:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

### 8. بناء الأصول (Assets)

```bash
npm run build
```

أو للتطوير:

```bash
npm run dev
```

### 9. إنشاء مفتاح التطبيق (إذا لم يكن موجوداً)

```bash
php artisan key:generate
```

## المستخدمين الافتراضيين

بعد تشغيل Seeders، سيتم إنشاء مستخدمين تجريبيين:

### Admin User
- **Email:** admin@example.com
- **Password:** password
- **Role:** admin

### Owner User
- **Email:** owner@example.com
- **Password:** password
- **Role:** owner

## البنية التنظيمية

### الأدوار والصلاحيات

- **Admin Role:**
  - Permission: `view admin dashboard`
  - Route: `/admin/dashboard`

- **Owner Role:**
  - Permission: `view owner dashboard`
  - Route: `/owner/dashboard`

### الملفات المهمة

1. **Models:**
   - `app/Models/User.php` - يحتوي على `HasRoles` trait

2. **Controllers:**
   - `app/Http/Controllers/Admin/DashboardController.php`
   - `app/Http/Controllers/Owner/DashboardController.php`

3. **Middleware:**
   - `app/Http/Middleware/EnsureUserHasRole.php`
   - مسجل في `bootstrap/app.php` كـ `role`

4. **Seeders:**
   - `database/seeders/RolePermissionSeeder.php`

5. **Responses:**
   - `app/Http/Responses/LoginResponse.php` - يعيد التوجيه بناءً على الدور

6. **Views:**
   - `resources/views/layouts/admin.blade.php`
   - `resources/views/layouts/owner.blade.php`
   - `resources/views/admin/dashboard.blade.php`
   - `resources/views/owner/dashboard.blade.php`

7. **Routes:**
   - `routes/web.php` - يحتوي على routes للوحات التحكم

## كيفية الاستخدام

### تسجيل الدخول

1. انتقل إلى `/login`
2. سجل الدخول باستخدام أحد المستخدمين الافتراضيين
3. سيتم إعادة توجيهك تلقائياً إلى لوحة التحكم الخاصة بدورك:
   - Admin → `/admin/dashboard`
   - Owner → `/owner/dashboard`

### حماية المسارات

يتم حماية المسارات باستخدام Middleware:

```php
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Admin routes
});
```

### إضافة أدوار جديدة

1. أنشئ Role جديد في Seeder:
```php
$newRole = Role::firstOrCreate(['name' => 'new_role']);
```

2. أنشئ Permission:
```php
Permission::firstOrCreate(['name' => 'view new_role dashboard']);
```

3. اربط Permission بالRole:
```php
$newRole->givePermissionTo('view new_role dashboard');
```

4. أنشئ Controller و View و Route جديد

## ملاحظات مهمة

- تأكد من تشغيل `php artisan migrate` بعد تثبيت Jetstream و Spatie Permission
- تأكد من تشغيل `npm run build` بعد تثبيت الحزم
- جميع المسارات محمية بـ Middleware `auth` و `role`
- إعادة التوجيه بعد تسجيل الدخول تتم تلقائياً بناءً على دور المستخدم

## استكشاف الأخطاء

### مشكلة: "Class 'Spatie\Permission\Models\Role' not found"
**الحل:** تأكد من تشغيل `composer require spatie/laravel-permission` و `composer dump-autoload`

### مشكلة: "Route [admin.dashboard] not defined"
**الحل:** تأكد من تشغيل `php artisan route:clear` و `php artisan config:clear`

### مشكلة: "Middleware [role] not found"
**الحل:** تأكد من تسجيل Middleware في `bootstrap/app.php`

## الدعم

للمزيد من المعلومات:
- [Laravel Jetstream Documentation](https://jetstream.laravel.com)
- [Spatie Permission Documentation](https://spatie.be/docs/laravel-permission)

