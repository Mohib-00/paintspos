<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('purchases', function (Blueprint $table) {
        $table->id();
        $table->string('receiving_location')->nullable();
        $table->string('vendors')->nullable();
        $table->string('invoice_no')->nullable();
        
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();

        $table->text('remarks')->nullable();
        $table->text('products')->nullable(); 

        $table->integer('quantity')->nullable();
        $table->decimal('purchase_rate', 10, 2)->nullable();
        $table->decimal('retail_rate', 10, 2)->nullable();
        $table->decimal('single_purchase_rate', 10, 2)->nullable();
        $table->decimal('single_retail_rate', 10, 2)->nullable();
 
        $table->decimal('totalquantity', 10, 2)->nullable();

        $table->decimal('gross_amount', 10, 2)->nullable();
        $table->decimal('discount', 10, 2)->nullable();
        $table->decimal('net_amount', 10, 2)->nullable();
        $table->string('payment_status')->default('pending');
        $table->string('payment_method')->nullable();
        $table->string('bank_name')->nullable();
        $table->decimal('amount_payed', 10, 2)->nullable();
        $table->decimal('amount_remain', 10, 2)->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
