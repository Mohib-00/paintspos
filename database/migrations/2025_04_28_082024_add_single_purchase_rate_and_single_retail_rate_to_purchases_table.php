<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('single_purchase_rate', 10, 2)->nullable()->after('purchase_rate');
            $table->decimal('single_retail_rate', 10, 2)->nullable()->after('retail_rate');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('single_purchase_rate');
            $table->dropColumn('single_retail_rate');
        });
    }
};
