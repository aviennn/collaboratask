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
            $table->string('navbar_color')->default('#333333');  // Default navbar color
            $table->string('sidebar_color')->default('#222222'); // Default sidebar color
            $table->string('body_color')->default('#ffffff');    // Default body/content-wrapper color
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['navbar_color', 'sidebar_color', 'body_color']);
        });
    }
};
