<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Log Controller for Admin
 * 
 * يعرض سجلات SMS و Email
 */
class LogController extends Controller
{
    /**
     * Display the SMS/Email logs page.
     */
    public function index()
    {
        return view('admin.logs.index');
    }
}

