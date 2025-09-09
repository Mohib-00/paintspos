<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sale_purchase_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_item_id');
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');

            $table->integer('quantity_deducted');
            $table->decimal('rate_deducted', 10, 2);

            $table->timestamps();

            $table->foreign('sale_item_id')->references('id')->on('sale_items')->onDelete('cascade');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('sale_purchase_tracking');
    }
};
