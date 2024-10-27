<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('task_id')->constrained()->onDelete('cascade'); // Links to a task
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to a user
            $table->string('category'); // Feedback category (e.g., Bug, Suggestion)
            $table->text('comment'); // Feedback comment
            $table->integer('rating')->nullable(); // Optional rating (1-5)
            $table->timestamps(); // Created at & Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
}
