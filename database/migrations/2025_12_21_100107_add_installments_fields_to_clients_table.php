<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * إضافة حقول الدفعات إلى جدول clients
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->boolean('has_installments')->default(false)->after('status'); // هل الدين على دفعات؟
            $table->integer('total_installments')->nullable()->after('has_installments'); // إجمالي عدد الدفعات
            $table->decimal('paid_amount', 10, 2)->default(0)->after('total_installments'); // إجمالي المبلغ المدفوع
            $table->decimal('remaining_amount', 10, 2)->nullable()->after('paid_amount'); // المبلغ المتبقي
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['has_installments', 'total_installments', 'paid_amount', 'remaining_amount']);
        });
    }
};
