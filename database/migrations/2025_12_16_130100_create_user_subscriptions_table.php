<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * إنشاء جدول user_subscriptions لتخزين الاشتراكات النشطة للمستخدمين
     */
    public function up(): void
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade'); // الباقة
            $table->foreignId('subscription_request_id')->nullable()->constrained()->onDelete('set null'); // طلب الاشتراك المرتبط
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active'); // حالة الاشتراك
            $table->timestamp('started_at')->useCurrent(); // تاريخ بدء الاشتراك
            $table->timestamp('expires_at')->nullable(); // تاريخ انتهاء الاشتراك (null للاشتراكات الدائمة)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};

