<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * AI Report Controller for Admin
 * 
 * يعرض تقارير AI
 */
class AiReportController extends Controller
{
    /**
     * Display the AI reports page.
     */
    public function index()
    {
        return view('admin.ai-reports.index');
    }
}

