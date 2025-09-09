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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('employee')->nullable();
            $table->string('customer_name')->nullable();
            $table->date('created_at')->nullable();
            $table->string('ref')->nullable();
            $table->integer('total_items')->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('sale_type', ['cash', 'credit'])->default('cash');
            $table->enum('payment_type', ['cash', 'bank'])->default('cash');
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('amount_after_discount', 10, 2)->default(0);
            $table->decimal('fixed_discount', 10, 2)->default(0);
            $table->decimal('amount_after_fix_discount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
