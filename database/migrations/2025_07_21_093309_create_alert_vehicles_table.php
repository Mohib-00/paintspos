<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('alert_vehicles', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('vehicle_id')->nullable();
        $table->text('alert')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();

        $table->foreign('vehicle_id')->references('id')->on('vehicle_records')->onDelete('cascade');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('alert_vehicles');
    }
};
