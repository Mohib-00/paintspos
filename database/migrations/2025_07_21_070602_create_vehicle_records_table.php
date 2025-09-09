<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('vehicle_records', function (Blueprint $table) {
        $table->id();
        $table->text('vehicle_no')->nullable();
        $table->text('owner_name')->nullable();
        $table->text('phone_no')->nullable();
        $table->text('currentoil_reading')->nullable();
        $table->text('nextoil_reading')->nullable(); 
        $table->text('oil_brand')->nullable();
        $table->text('quantity')->nullable();
        $table->text('gear_oil')->nullable();
        $table->text('oil_filter')->nullable(); 
        $table->text('air_filter')->nullable();
        $table->text('Ac_filter')->nullable();
        $table->text('battery_checkup')->nullable();
        $table->text('type_air_pressure')->nullable();
        $table->text('invoice_no')->nullable();
        $table->text('total_bill')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('vehicle_records');
    }
};
