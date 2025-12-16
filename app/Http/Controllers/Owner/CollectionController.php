<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Collection Controller for Owner
 * 
 * يعرض حالة التحصيل (New, Contacted, Promise to Pay, Paid, Overdue, Failed)
 */
class CollectionController extends Controller
{
    /**
     * Display the collection status page.
     */
    public function index()
    {
        return view('owner.collections.index');
    }
}

