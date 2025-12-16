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
     * إنشاء جدول collection_campaigns لتخزين حملات التحصيل
     */
    public function up(): void
    {
        Schema::create('collection_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade'); // المالك
            $table->string('campaign_number')->unique(); // رقم الحملة
            $table->enum('channel', ['sms', 'email']); // قناة الإرسال
            $table->string('template')->nullable(); // قالب الرسالة
            $table->text('message'); // نص الرسالة
            $table->enum('send_type', ['now', 'scheduled'])->default('now'); // نوع الإرسال
            $table->timestamp('scheduled_at')->nullable(); // وقت الجدولة
            $table->enum('status', ['pending', 'sent', 'scheduled', 'failed'])->default('pending'); // حالة الإرسال
            $table->integer('total_recipients')->default(0); // عدد المستلمين
            $table->integer('sent_count')->default(0); // عدد المرسل
            $table->integer('failed_count')->default(0); // عدد الفاشل
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_campaigns');
    }
};
