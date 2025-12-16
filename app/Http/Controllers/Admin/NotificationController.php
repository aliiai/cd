<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Notification Controller for Admin
 * 
 * يدير الإشعارات والتنبيهات
 */
class NotificationController extends Controller
{
    /**
     * Display the notifications/alerts page.
     */
    public function index()
    {
        return view('admin.notifications.index');
    }
}

