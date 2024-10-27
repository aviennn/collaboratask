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
        $table->string('full_name')->nullable();
        $table->text('bio')->nullable();
        $table->text('about_me')->nullable();
        $table->string('address')->nullable();
        $table->json('skills')->nullable(); // Assuming skills is a JSON array
        $table->string('education')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['full_name', 'bio', 'about_me', 'address', 'skills', 'education']);
    });
}

};
