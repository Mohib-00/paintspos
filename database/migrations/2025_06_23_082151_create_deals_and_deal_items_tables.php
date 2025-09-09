<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    // Create deals table
    Schema::create('deals', function (Blueprint $table) {
        $table->id();
        $table->string('deal_name');
        $table->decimal('deal_price', 10, 2);
        $table->text('remarks')->nullable();
        $table->timestamps();
    });

    // Create deal_items table
    Schema::create('deal_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('deal_id')->constrained('deals')->onDelete('cascade');
        $table->string('products');
        $table->integer('quantity');
        $table->decimal('single_purchase_rate', 10, 2);
        $table->timestamps();
    });
}

};
