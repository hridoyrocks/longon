<?php
// database/migrations/xxxx_create_match_analytics_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('match_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->onDelete('cascade');
            $table->integer('total_goals');
            $table->integer('total_cards');
            $table->integer('total_substitutions');
            $table->integer('overlay_views')->default(0);
            $table->integer('overlay_unique_views')->default(0);
            $table->json('viewer_countries')->nullable();
            $table->json('peak_viewing_times')->nullable();
            $table->integer('average_viewing_duration')->default(0); // in seconds
            $table->json('event_timeline')->nullable();
            $table->decimal('engagement_score', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('match_analytics');
    }
};