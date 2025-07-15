<?php
// database/migrations/xxxx_create_reseller_customers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reseller_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referrer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('total_purchases', 10, 2)->default(0);
            $table->decimal('total_commission_earned', 10, 2)->default(0);
            $table->timestamp('first_purchase_at')->nullable();
            $table->timestamp('last_purchase_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reseller_customers');
    }
};