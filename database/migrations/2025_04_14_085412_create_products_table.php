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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name')->nullable();
            $table->string('category_name')->nullable();
            $table->string('subcategory_name')->nullable();
            $table->string(column: 'item_name')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('purchase_rate', 10, 2)->nullable();
            $table->decimal('retail_rate', 10, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('single_purchase_rate')->nullable();
            $table->integer('single_retail_rate')->nullable();
   
            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
