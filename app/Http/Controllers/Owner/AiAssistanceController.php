<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * AI Assistance Controller for Owner
 * 
 * يعرض صفحة المساعدة بالذكاء الاصطناعي
 */
class AiAssistanceController extends Controller
{
    /**
     * Display the AI assistance page.
     */
    public function index()
    {
        return view('owner.ai-assistance.index');
    }
}

