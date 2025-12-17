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
            
            // Users Management
            'manage users',
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Subscriptions Management
            'manage subscriptions',
            'view subscriptions',
            'create subscriptions',
            'edit subscriptions',
            'delete subscriptions',
            
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
            
            // System Settings
            'manage system settings',
            'view system settings',
            
            // Support Tickets
            'manage support tickets',
            'view support tickets',
            
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
            'manage subscriptions',
            'view subscriptions',
            'view reports',
            'view ai reports',
            'view messages logs',
            'manage support tickets',
            'view support tickets',
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

