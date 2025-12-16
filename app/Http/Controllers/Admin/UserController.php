<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * User Controller for Admin
 * 
 * هذا Controller يدير صفحة Users في لوحة تحكم Admin
 */
class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        return view('admin.users.index');
    }
}

