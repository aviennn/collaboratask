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
    Schema::table('tasks', function (Blueprint $table) {
        // Modify date_started to be a timestamp instead of date
        $table->timestamp('date_started')->nullable()->change();
        
        // Add a new column to track when the task was completed
        $table->timestamp('date_completed')->nullable();
    });
}

public function down()
{
    Schema::table('tasks', function (Blueprint $table) {
        // Roll back the changes
        $table->date('date_started')->nullable()->change();
        $table->dropColumn('date_completed');
    });
}

};
