<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Settings Controller for Admin
 * 
 * هذا Controller يدير صفحة Settings في لوحة تحكم Admin
 */
class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        return view('admin.settings');
    }
}

