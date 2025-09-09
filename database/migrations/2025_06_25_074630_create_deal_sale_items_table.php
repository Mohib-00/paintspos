<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deal_sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');          
            $table->unsignedBigInteger('sale_item_id');     
            $table->string('deal_product_name');
            $table->integer('deal_product_quantity');
            $table->decimal('deal_product_purchase_rate', 10, 2);
            $table->decimal('deal_product_retail_rate', 10, 2);
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('sale_item_id')->references('id')->on('sale_items')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deal_sale_items');
    }
};

