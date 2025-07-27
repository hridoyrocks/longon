<?php
// database/migrations/xxxx_create_matches_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('team_a');
            $table->string('team_b');
            $table->integer('team_a_score')->default(0);
            $table->integer('team_b_score')->default(0);
            $table->enum('status', ['pending', 'live', 'finished'])->default('pending');
            $table->integer('match_time')->default(0); // in minutes
            $table->boolean('is_premium')->default(false);
            $table->json('overlay_settings')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('matches');
    }
};