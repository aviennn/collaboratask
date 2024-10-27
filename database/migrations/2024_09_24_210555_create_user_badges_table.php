<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBadgesTable extends Migration
{
    public function up()
    {
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // User reference
            $table->foreignId('badge_id')->constrained()->onDelete('cascade');  // Badge reference
            $table->timestamp('earned_at')->useCurrent();  // When the badge was earned
            $table->timestamps();  // Laravel's created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_badges');
    }
}
