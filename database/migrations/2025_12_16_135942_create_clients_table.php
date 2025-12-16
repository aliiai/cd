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
     * إنشاء جدول clients لتخزين بيانات المديونين
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade'); // المالك
            $table->string('name'); // الاسم الكامل
            $table->string('phone'); // رقم الهاتف
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->decimal('debt_amount', 10, 2); // قيمة الدين
            $table->date('due_date'); // تاريخ الاستحقاق
            $table->string('payment_link')->nullable(); // رابط الدفع
            $table->text('notes')->nullable(); // ملاحظات
            $table->enum('status', ['new', 'contacted', 'promise_to_pay', 'paid', 'overdue', 'failed'])->default('new'); // حالة الدين
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
