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
        $table->string('approval_status')->nullable(); // Pending, accepted, or rejected
        $table->text('rejection_remarks')->nullable();  // Reason for rejection
    });
}

public function down()
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->dropColumn(['approval_status', 'rejection_remarks']);
    });
}

};
