<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     * 
     * إنشاء جدول subscription_requests لتخزين طلبات الاشتراك
     */
    public function up(): void
    {
        Schema::create('subscription_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المالك الذي طلب الاشتراك
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade'); // الباقة المطلوبة
            $table->string('payment_proof')->nullable(); // مسار صورة الدفع
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // حالة الطلب
            $table->text('admin_notes')->nullable(); // ملاحظات الـ Admin
            $table->timestamp('approved_at')->nullable(); // تاريخ الموافقة
            $table->timestamp('rejected_at')->nullable(); // تاريخ الرفض
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // من وافق على الطلب
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_requests');
    }
};
