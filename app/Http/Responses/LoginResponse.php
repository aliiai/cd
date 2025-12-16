<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
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
        
        // إذا لم يكن للمستخدم دور، إعطاؤه دور 'owner' افتراضياً
        // ثم إعادة التوجيه إلى لوحة تحكم Owner
        $user->assignRole('owner');
        return redirect()->route('owner.dashboard');
    }
}

