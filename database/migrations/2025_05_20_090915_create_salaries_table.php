<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('salaries', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('employee_id')->nullable();
        $table->decimal('paid', 10, 2)->nullable();
        $table->decimal('bonus', 10, 2)->nullable();
        $table->timestamps();

        $table->foreign('employee_id')->references('id')->on('emplyees')->onDelete('cascade');
    });
}


    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
