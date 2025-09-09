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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('unit_purchase_rate', 8, 2)->change();
            $table->decimal('unit_retail_rate', 8, 2)->change();
            $table->decimal('single_carton_purchase', 8, 2)->change();
            $table->decimal('single_carton_retail', 8, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->float('unit_purchase_rate')->change();
            $table->float('unit_retail_rate')->change();
            $table->float('single_carton_purchase')->change();
            $table->float('single_carton_retail')->change();
        });
    }
};
