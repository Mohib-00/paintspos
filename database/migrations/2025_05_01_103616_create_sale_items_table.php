<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('sale_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('sale_id'); 
        $table->string('product_name'); 
        $table->integer('product_quantity'); 
        $table->decimal('product_rate', 10, 2); 
        $table->decimal('product_subtotal', 10, 2); 
        $table->timestamps();

        $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
