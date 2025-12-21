<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * إنشاء جدول debt_installments لتخزين دفعات الدين
     */
    public function up(): void
    {
        Schema::create('debt_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debtor_id')->constrained('clients')->onDelete('cascade'); // المديون
            $table->integer('installment_number'); // رقم الدفعة (1, 2, 3...)
            $table->decimal('amount', 10, 2); // مبلغ الدفعة
            $table->date('due_date'); // تاريخ استحقاق الدفعة
            $table->date('paid_date')->nullable(); // تاريخ الدفع الفعلي
            $table->decimal('paid_amount', 10, 2)->default(0); // المبلغ المدفوع
            $table->enum('status', ['pending', 'partial', 'paid', 'overdue', 'cancelled'])->default('pending'); // حالة الدفعة
            $table->string('payment_proof')->nullable(); // إثبات الدفع (صورة/ملف)
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
            
            // Indexes
            $table->index(['debtor_id', 'status']);
            $table->index('due_date');
            $table->index('installment_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debt_installments');
    }
};
