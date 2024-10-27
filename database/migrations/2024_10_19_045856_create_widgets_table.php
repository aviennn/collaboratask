<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetsTable extends Migration
{
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // The user who owns the widget
            $table->string('type'); // The type of the widget (e.g., 'note')
            $table->text('content')->nullable(); // Content of the widget (e.g., note text)
            $table->timestamps();
            
            // Foreign key to link to the users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('widgets');
    }
}
