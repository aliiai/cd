<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Message Controller for Owner
 * 
 * يدير إرسال SMS و Email
 */
class MessageController extends Controller
{
    /**
     * Show the form for creating a new message (SMS/Email).
     */
    public function create()
    {
        return view('owner.messages.create');
    }
}

