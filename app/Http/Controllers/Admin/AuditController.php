<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Audit Controller for Admin
 * 
 * يعرض سجلات التدقيق والمراجعة
 */
class AuditController extends Controller
{
    /**
     * Display the audit logs page.
     */
    public function index()
    {
        return view('admin.audit.index');
    }
}

