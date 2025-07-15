<?php
// database/migrations/xxxx_add_reseller_fields_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('referrer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('commission_balance', 10, 2)->default(0);
            $table->decimal('total_commission_earned', 10, 2)->default(0);
            $table->decimal('total_commission_paid', 10, 2)->default(0);
            $table->integer('total_referrals')->default(0);
            $table->timestamp('reseller_approved_at')->nullable();
            $table->json('reseller_settings')->nullable();
            $table->string('business_name')->nullable();
            $table->decimal('monthly_sales_target', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'referrer_id', 'commission_balance', 'total_commission_earned',
                'total_commission_paid', 'total_referrals', 'reseller_approved_at',
                'reseller_settings', 'business_name', 'monthly_sales_target'
            ]);
        });
    }
};