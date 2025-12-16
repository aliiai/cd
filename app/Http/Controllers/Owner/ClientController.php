<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Client Controller for Owner
 * 
 * هذا Controller يدير صفحة Clients في لوحة تحكم Owner
 */
class ClientController extends Controller
{
    /**
     * Display a listing of clients
     */
    public function index()
    {
        return view('owner.clients.index');
    }
}

