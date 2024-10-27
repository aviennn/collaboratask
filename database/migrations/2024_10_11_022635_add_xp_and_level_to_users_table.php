<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddXpAndLevelToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('xp')->default(0);   // New users start with 0 XP
            $table->integer('level')->default(1); // New users start at level 1
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('xp');
            $table->dropColumn('level');
        });
    }
}