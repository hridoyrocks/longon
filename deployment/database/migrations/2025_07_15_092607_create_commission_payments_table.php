<?php
// database/migrations/xxxx_create_commission_payments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commission_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('payment_request_id')->constrained()->onDelete('cascade');
            $table->decimal('purchase_amount', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->integer('commission_percentage');
            $table->integer('commission_level'); // 1 or 2
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commission_payments');
    }
};