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
        $table->unsignedBigInteger('selected_title_id')->nullable()->after('selected_border');

        // Optional: Add a foreign key constraint if you want
        $table->foreign('selected_title_id')->references('id')->on('titles')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['selected_title_id']);
        $table->dropColumn('selected_title_id');
    });
}
};
