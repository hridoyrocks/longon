<?php
// database/migrations/xxxx_create_match_events_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->onDelete('cascade');
            $table->enum('event_type', ['goal', 'yellow_card', 'red_card', 'substitution', 'penalty']);
            $table->enum('team', ['team_a', 'team_b']);
            $table->string('player')->nullable();
            $table->integer('minute');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('match_events');
    }
};