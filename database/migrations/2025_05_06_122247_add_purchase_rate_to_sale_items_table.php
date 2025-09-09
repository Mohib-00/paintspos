<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('sale_items', function (Blueprint $table) {
        $table->decimal('purchase_rate', 10, 2)->after('product_quantity')->nullable();
    });
}

public function down()
{
    Schema::table('sale_items', function (Blueprint $table) {
        $table->dropColumn('purchase_rate');
    });
}

};
