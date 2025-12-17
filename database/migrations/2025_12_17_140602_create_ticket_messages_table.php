<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * إنشاء جدول ticket_messages لتخزين الردود على الشكاوى
     */
    public function up(): void
    {
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade'); // الشكوى
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المرسل (Owner أو Admin)
            $table->text('message'); // نص الرسالة
            $table->string('attachment')->nullable(); // مرفق (صورة)
            $table->boolean('is_admin')->default(false); // هل الرسالة من الأدمن؟
            $table->timestamps();
            
            // Indexes
            $table->index('ticket_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};
