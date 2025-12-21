<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * إضافة 'auto' إلى enum send_type في جدول collection_campaigns
     */
    public function up(): void
    {
        // في MySQL، نحتاج إلى تعديل enum يدوياً
        DB::statement("ALTER TABLE collection_campaigns MODIFY COLUMN send_type ENUM('now', 'scheduled', 'auto') DEFAULT 'now'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع enum إلى حالته السابقة
        DB::statement("ALTER TABLE collection_campaigns MODIFY COLUMN send_type ENUM('now', 'scheduled') DEFAULT 'now'");
    }
};
