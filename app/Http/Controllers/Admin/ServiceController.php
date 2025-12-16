<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Service Controller for Admin
 * 
 * يدير عرض وإدارة الخدمات ومقدمي الخدمات
 */
class ServiceController extends Controller
{
    /**
     * Display the services management page.
     */
    public function index()
    {
        return view('admin.services.index');
    }
}

