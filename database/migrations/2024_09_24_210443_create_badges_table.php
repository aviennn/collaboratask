<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBadgesTable extends Migration
{
    public function up()
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Name of the badge
            $table->text('description');  // Description of the badge
            $table->string('icon')->nullable();  // Path to the badge icon (optional)
            $table->string('criteria')->nullable();  // Criteria for earning the badge
            $table->timestamps();  // Laravel's created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('badges');
    }
}

