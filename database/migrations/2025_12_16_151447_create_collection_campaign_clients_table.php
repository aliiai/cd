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
     * إنشاء جدول collection_campaign_clients لربط الحملات بالمديونين
     */
    public function up(): void
    {
        Schema::create('collection_campaign_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('collection_campaigns')->onDelete('cascade'); // الحملة
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade'); // المديون
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending'); // حالة الإرسال للمديون
            $table->timestamp('sent_at')->nullable(); // وقت الإرسال
            $table->text('error_message')->nullable(); // رسالة الخطأ إن وجدت
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_campaign_clients');
    }
};
