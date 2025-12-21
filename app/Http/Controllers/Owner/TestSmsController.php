<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\FourJawalyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Test SMS Controller
 * 
 * للاختبار فقط - يمكن حذفه لاحقاً
 */
class TestSmsController extends Controller
{
    public function test(Request $request)
    {
        $phone = $request->input('phone');
        $message = $request->input('message', 'رسالة تجريبية من النظام');
        
        if (!$phone) {
            return response()->json([
                'error' => 'يرجى إدخال رقم الهاتف'
            ], 400);
        }
        
        $smsService = new FourJawalyService();
        $result = $smsService->sendSMS($phone, $message);
        
        return response()->json([
            'result' => $result,
            'logs' => 'تحقق من storage/logs/laravel.log للتفاصيل الكاملة'
        ]);
    }
}

