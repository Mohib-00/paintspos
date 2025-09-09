<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['unit_purchase_rate', 'unit_retail_rate']);
    });

    Schema::table('products', function (Blueprint $table) {
        $table->integer('unit_purchase_rate')->nullable()->after('quantity');
        $table->integer('unit_retail_rate')->nullable()->after('unit_purchase_rate');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['unit_purchase_rate', 'unit_retail_rate']);
    });

    Schema::table('products', function (Blueprint $table) {
        $table->integer('unit_purchase_rate')->nullable();
        $table->integer('unit_retail_rate')->nullable();
    });
}

};
