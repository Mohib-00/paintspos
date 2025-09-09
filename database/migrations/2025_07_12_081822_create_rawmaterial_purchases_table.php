<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('rawmaterial_purchases', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('receiving_location')->nullable();
        $table->string('vendors')->nullable();
        $table->string('invoice_no')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->text('remarks')->nullable();
        $table->json('single_purchase_rate')->nullable();
        $table->json('products')->nullable();
        $table->json('quantity')->nullable();
        $table->json('purchase_rate')->nullable();
        $table->decimal('totalquantity', 10, 2)->nullable();
        $table->decimal('gross_amount', 10, 2)->nullable();
        $table->decimal('discount', 10, 2)->default(0);
        $table->decimal('net_amount', 10, 2)->nullable();
        $table->string('stock_status')->default('complete');

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('rawmaterial_purchases');
    }
};
