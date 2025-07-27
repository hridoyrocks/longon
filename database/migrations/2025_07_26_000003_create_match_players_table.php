<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->enum('team', ['home', 'away']);
            $table->boolean('is_starting_11')->default(true);
            $table->boolean('is_substitute')->default(false);
            $table->timestamps();
            
            $table->unique(['match_id', 'player_id']);
            $table->index(['match_id', 'team']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_players');
    }
};
