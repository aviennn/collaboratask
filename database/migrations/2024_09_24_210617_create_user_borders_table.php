<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBordersTable extends Migration
{
    public function up()
    {
        Schema::create('user_borders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // User reference
            $table->foreignId('border_id')->constrained()->onDelete('cascade');  // Border reference
            $table->timestamp('unlocked_at')->useCurrent();  // When the border was unlocked
            $table->boolean('is_active')->default(false);  // Whether the border is active
            $table->timestamps();  // Laravel's created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_borders');
    }
}
