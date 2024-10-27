<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->json('dashboard_layout')->nullable(); // Add a JSON field to store the layout
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('dashboard_layout');
    });
}

};
