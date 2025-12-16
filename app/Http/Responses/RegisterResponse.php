<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

/**
 * Register Response
 * 
 * هذا الـ Response يعيد توجيه المستخدم بعد التسجيل بناءً على دوره
 */
class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();
        
        // التحقق من دور المستخدم وإعادة التوجيه
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('owner')) {
            return redirect()->route('owner.dashboard');
        }
        
        // إذا لم يكن للمستخدم دور (يجب ألا يحدث هذا لأننا نعطيه دور في CreateNewUser)
        // لكن للاحتياط، إعطاؤه دور 'owner' ثم إعادة التوجيه
        $user->assignRole('owner');
        return redirect()->route('owner.dashboard');
    }
}

