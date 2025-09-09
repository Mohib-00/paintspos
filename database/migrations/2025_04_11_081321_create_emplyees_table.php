<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emplyees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name')->nullable();
            $table->string('area_id')->nullable();
            $table->string('assigned_user_id')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('client_gender')->nullable();
            $table->string('client_cnic')->nullable();
            $table->string('client_father_name')->nullable();
            $table->string('client_residence')->nullable();
            $table->string('client_salary')->nullable();
            $table->decimal('client_permanent_address')->nullable();
            $table->decimal('client_residential_address')->nullable();
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emplyees');
    }
};
