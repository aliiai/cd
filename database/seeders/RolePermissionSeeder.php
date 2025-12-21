<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الصلاحيات الأساسية
        $permissions = [
            // Dashboard
            'view admin dashboard',
            'view owner dashboard',
            
            // Users Management (مع منطق: create/edit/delete تظهر فقط بعد view)
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Subscriptions Management (مع منطق: create/edit/delete تظهر فقط بعد view)
            'view subscriptions',
            'create subscriptions',
            'edit subscriptions',
            'delete subscriptions',
            
            // Audit Logs
            'view audit logs',
            
            // System Settings (view فقط أو view + change)
            'view system settings',
            'change system settings',
            
            // Support Tickets (view أو لا، manage أو لا)
            'view support tickets',
            'manage support tickets',
            
            // Reports
            'view reports',
            'view ai reports',
            
            // Campaigns
            'manage campaigns',
            'view campaigns',
            
            // Debtors
            'manage debtors',
            'view debtors',
            
            // Messages Logs
            'view messages logs',
            
            // Admins Management
            'manage admins',
            'view admins',
            'create admins',
            'edit admins',
            'delete admins',
            
            // Permissions Management
            'manage permissions',
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            
            // Roles Management
            'manage roles',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // إنشاء الأدوار
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $ownerRole = Role::firstOrCreate(['name' => 'owner']);
        
        // إنشاء دور Super Admin
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);

        // تعيين جميع الصلاحيات لـ Super Admin
        $superAdminRole->givePermissionTo(Permission::all());
        
        // تعيين الصلاحيات الأساسية للأدوار
        $adminRole->givePermissionTo([
            'view admin dashboard',
            'manage users',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage subscriptions',
            'view subscriptions',
            'create subscriptions',
            'edit subscriptions',
            'delete subscriptions',
            'view reports',
            'view ai reports',
            'view messages logs',
            'manage support tickets',
            'view support tickets',
            // إدارة الأدمن والصلاحيات (للمستخدمين الذين لديهم دور admin)
            'view admins',
            'view permissions',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'create permissions',
            'edit permissions',
            'delete permissions',
            // سجلات التدقيق والإعدادات
            'view audit logs',
            'view system settings',
            'change system settings',
        ]);
        
        $ownerRole->givePermissionTo('view owner dashboard');

        // إنشاء مستخدمين تجريبيين (اختياري)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        $owner = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Owner User',
                'password' => Hash::make('password'),
            ]
        );
        $owner->assignRole('owner');
    }
}

