<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Change the 'single_purchase_rate' and 'single_retail_rate' columns to 'json' type
            $table->json('single_purchase_rate')->nullable()->change();
            $table->json('single_retail_rate')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Rollback: Change the columns back to 'decimal' type
            $table->decimal('single_purchase_rate', 10, 2)->nullable()->change();
            $table->decimal('single_retail_rate', 10, 2)->nullable()->change();
        });
    }
};
