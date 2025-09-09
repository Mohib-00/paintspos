<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('raw_materials', function (Blueprint $table) {
        $table->id();
        $table->string('brand_name')->nullable();
        $table->string('category_name')->nullable();
        $table->string('item_name')->nullable();
        $table->decimal('purchase_rate', 10, 2)->nullable();
        $table->string('quantity')->nullable(); 
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};
