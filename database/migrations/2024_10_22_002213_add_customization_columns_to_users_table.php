<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('background_color')->default('#ffffff'); // Default to white background
            $table->string('font_color')->default('#000000');       // Default to black text
            $table->string('font_size')->default('16px');           // Default font size
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['background_color', 'font_color', 'font_size']);
        });
    }
};
