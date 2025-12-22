<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaymobService;
use Illuminate\Support\Facades\Log;

class TestPaymentLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:test-link 
                            {--amount=50 : المبلغ بالريال السعودي}
                            {--name= : اسم المديون}
                            {--email= : البريد الإلكتروني}
                            {--phone= : رقم الهاتف}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'اختبار توليد رابط الدفع عبر PayMob';

    /**
     * Execute the console command.
     */
    public function handle(PaymobService $paymobService)
    {
        $this->info('🚀 بدء اختبار توليد رابط الدفع...');
        $this->newLine();

        // جمع البيانات
        $amount = (float) $this->option('amount');
        $name = $this->option('name') ?? 'مستخدم تجريبي';
        $email = $this->option('email') ?? 'test@example.com';
        $phone = $this->option('phone') ?? '+966500000000';

        $this->info('📋 بيانات الاختبار:');
        $this->table(
            ['المعامل', 'القيمة'],
            [
                ['المبلغ', $amount . ' ر.س'],
                ['الاسم', $name],
                ['البريد الإلكتروني', $email],
                ['رقم الهاتف', $phone],
            ]
        );
        $this->newLine();

        // التحقق من الإعدادات
        $this->info('🔍 التحقق من إعدادات PayMob...');
        $apiKey = config('services.paymob.api_key');
        $integrationId = config('services.paymob.integration_id');
        $iframeId = config('services.paymob.iframe_id');
        $merchantId = config('services.paymob.merchant_id');

        $configStatus = [];
        $configStatus[] = ['API Key', !empty($apiKey) ? '✅ موجود (' . strlen($apiKey) . ' حرف)' : '❌ غير موجود'];
        $configStatus[] = ['Integration ID', !empty($integrationId) ? '✅ موجود' : '❌ غير موجود'];
        $configStatus[] = ['iFrame ID', !empty($iframeId) ? '✅ موجود' : '❌ غير موجود'];
        $configStatus[] = ['Merchant ID', !empty($merchantId) ? '✅ موجود' : '❌ غير موجود'];

        $this->table(['الإعداد', 'الحالة'], $configStatus);
        $this->newLine();

        if (empty($apiKey) || empty($integrationId) || empty($iframeId) || empty($merchantId)) {
            $this->error('❌ إعدادات PayMob غير مكتملة!');
            $this->warn('يرجى التحقق من ملف .env وإضافة جميع الإعدادات المطلوبة.');
            return 1;
        }

        // اختبار Authentication
        $this->info('🔐 اختبار المصادقة مع PayMob...');
        try {
            $authData = $paymobService->authenticate();
            
            if (!$authData || !isset($authData['token'])) {
                $this->error('❌ فشل المصادقة مع PayMob!');
                $this->warn('تحقق من API Key في ملف .env');
                return 1;
            }

            $this->info('✅ تمت المصادقة بنجاح!');
            $this->line('   Token Length: ' . strlen($authData['token']) . ' حرف');
            if (isset($authData['profile_id'])) {
                $this->line('   Profile ID: ' . $authData['profile_id']);
            }
            $this->newLine();
        } catch (\Exception $e) {
            $this->error('❌ خطأ في المصادقة: ' . $e->getMessage());
            return 1;
        }

        // اختبار توليد رابط الدفع
        $this->info('🔗 توليد رابط الدفع...');
        try {
            $result = $paymobService->generatePaymentLink(
                amount: $amount,
                debtorName: $name,
                debtorEmail: $email,
                debtorPhone: $phone,
                debtorId: null, // لا نحفظ في قاعدة البيانات للاختبار
                installmentId: null,
                currency: 'SAR'
            );

            if (!$result || !isset($result['payment_link'])) {
                $this->error('❌ فشل توليد رابط الدفع!');
                $this->warn('تحقق من السجلات في storage/logs/laravel.log');
                return 1;
            }

            $this->newLine();
            $this->info('✅ تم توليد رابط الدفع بنجاح!');
            $this->newLine();
            
            $this->info('📊 معلومات رابط الدفع:');
            $this->table(
                ['المعلومة', 'القيمة'],
                [
                    ['رابط الدفع', $result['payment_link']],
                    ['PayMob Order ID', $result['paymob_order_id'] ?? 'N/A'],
                    ['Payment Transaction ID', $result['payment_transaction_id'] ?? 'N/A (اختبار)'],
                ]
            );
            $this->newLine();

            $this->info('🌐 رابط الدفع:');
            $this->line($result['payment_link']);
            $this->newLine();

            $this->info('💡 يمكنك فتح الرابط في المتصفح لاختبار صفحة الدفع.');
            $this->newLine();

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ خطأ في توليد رابط الدفع: ' . $e->getMessage());
            $this->warn('تحقق من السجلات في storage/logs/laravel.log');
            Log::error('Test payment link error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}

