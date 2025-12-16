<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Role Controller for Admin
 * 
 * يدير عرض وإدارة الأدوار والصلاحيات
 */
class RoleController extends Controller
{
    /**
     * Display the roles and permissions management page.
     */
    public function index()
    {
        return view('admin.roles.index');
    }
}

