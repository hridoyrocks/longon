<?php
// database/migrations/xxxx_add_credits_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('credits_balance', 8, 2)->default(1.00); // 1 free credit
            $table->string('phone')->nullable();
            $table->enum('user_type', ['user', 'reseller', 'admin'])->default('user');
            $table->string('referral_code')->unique()->nullable();
            $table->decimal('total_spent', 10, 2)->default(0);
            $table->timestamp('last_activity')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['credits_balance', 'phone', 'user_type', 'referral_code', 'total_spent', 'last_activity']);
        });
    }
};