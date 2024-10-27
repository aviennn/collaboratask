<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBordersTable extends Migration
{
    public function up()
    {
        Schema::create('borders', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Name of the border
            $table->text('description');  // Description of the border
            $table->string('image')->nullable();  // Path to the border image
            $table->string('criteria')->nullable();  // Criteria for unlocking the border
            $table->timestamps();  // Laravel's created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('borders');
    }
}
