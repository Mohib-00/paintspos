<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('area_id')->nullable();
            $table->string('client_type')->nullable();
            $table->string('assigned_user_id')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('client_gender')->nullable();
            $table->string('client_cnic')->nullable();
            $table->string('client_father_name')->nullable();
            $table->string('client_residence')->nullable();
            $table->string('client_occupation')->nullable();
            $table->decimal('client_salary', 8, 2)->nullable();
            $table->decimal('client_fixed_discount', 8, 2)->nullable();
            $table->decimal('distributor_fix_margin', 8, 2)->nullable();
            $table->string('client_permanent_address')->nullable();
            $table->string('client_residential_address')->nullable();
            $table->string('client_office_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
}
