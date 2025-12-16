<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Settings Controller for Owner
 * 
 * هذا Controller يدير صفحة Settings في لوحة تحكم Owner
 */
class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        return view('owner.settings');
    }
}

