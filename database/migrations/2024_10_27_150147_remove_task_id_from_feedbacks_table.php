<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTaskIdFromFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            // Remove the task_id column
            $table->dropForeign(['task_id']); // If there's a foreign key
            $table->dropColumn('task_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            // Re-add the task_id column if needed
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
}
