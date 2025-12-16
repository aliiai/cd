<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Report Controller for Owner
 * 
 * هذا Controller يدير صفحة Reports في لوحة تحكم Owner
 */
class ReportController extends Controller
{
    /**
     * Display a listing of reports
     */
    public function index()
    {
        return view('owner.reports.index');
    }
}

