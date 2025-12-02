<?php
// database/migrations/xxxx_create_commission_tiers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commission_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('min_monthly_sales', 10, 2);
            $table->decimal('max_monthly_sales', 10, 2)->nullable();
            $table->integer('level_1_commission'); // Percentage
            $table->integer('level_2_commission'); // Percentage
            $table->integer('bonus_percentage')->default(0);
            $table->json('benefits')->nullable(); // Extra benefits
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commission_tiers');
    }
};