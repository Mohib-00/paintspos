<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
    Schema::create('fines', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('employee_id');
    $table->foreign('employee_id')->references('id')->on('emplyees')->onDelete('cascade');

    $table->text('narration')->nullable();
    $table->decimal('fine', 10, 2)->nullable();

    $table->timestamp('created_at')->nullable();
    $table->timestamp('updated_at')->nullable();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
