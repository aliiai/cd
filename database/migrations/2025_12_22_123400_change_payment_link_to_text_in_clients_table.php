<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * تغيير نوع عمود payment_link من string إلى text
     * لأن روابط PayMob طويلة جداً (أكثر من 1000 حرف)
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->text('payment_link')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('payment_link')->nullable()->change();
        });
    }
};

