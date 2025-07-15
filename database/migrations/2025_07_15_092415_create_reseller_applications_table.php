<?php
// database/migrations/xxxx_create_reseller_applications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reseller_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name');
            $table->string('business_type');
            $table->text('business_address');
            $table->string('nid_number');
            $table->string('bank_account');
            $table->string('bank_name');
            $table->string('bank_branch');
            $table->string('mobile_banking_number');
            $table->enum('mobile_banking_type', ['bkash', 'nagad', 'rocket']);
            $table->text('experience_description');
            $table->decimal('expected_monthly_sales', 10, 2);
            $table->json('documents')->nullable(); // NID, Bank statement, etc.
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reseller_applications');
    }
};