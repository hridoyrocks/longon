<?php
// database/migrations/xxxx_create_credit_transactions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('match_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('payment_request_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('credits_used', 8, 2);
            $table->enum('transaction_type', ['credit', 'debit', 'refund']);
            $table->decimal('balance_before', 8, 2);
            $table->decimal('balance_after', 8, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('credit_transactions');
    }
};