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
     * إنشاء جدول subscriptions لتخزين معلومات الباقات
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الباقة
            $table->text('description')->nullable(); // وصف الباقة
            $table->decimal('price', 10, 2); // السعر
            $table->integer('max_debtors')->default(0); // عدد المديونين المسموح به
            $table->integer('max_messages')->default(0); // عدد الرسائل المسموح بها
            $table->boolean('ai_enabled')->default(false); // إمكانية استخدام الذكاء الاصطناعي
            $table->boolean('is_active')->default(true); // حالة الباقة (نشط/غير نشط)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
