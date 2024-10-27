<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('team_id')->constrained()->onDelete('cascade'); // Team ID
            $table->foreignId('inviter_id')->constrained('users')->onDelete('cascade'); // Inviter (team leader or creator)
            $table->foreignId('invitee_id')->constrained('users')->onDelete('cascade'); // Invitee (user receiving the invite)
            $table->string('email'); // Invitee's email
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending'); // Invitation status
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
