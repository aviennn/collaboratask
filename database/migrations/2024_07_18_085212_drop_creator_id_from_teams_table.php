<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCreatorIdFromTeamsTable extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['creator_id']);
            // Then drop the column
            $table->dropColumn('creator_id');
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            // Add the column back
            $table->unsignedBigInteger('creator_id')->nullable();
            // Re-add the foreign key constraint
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
