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
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('payment_type', ['cash', 'bank'])->default('cash')->nullable()->change();
            $table->decimal('fixed_discount', 10, 2)->default(0)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('payment_type', ['cash', 'bank'])->default('cash')->nullable(false)->change();
            $table->decimal('fixed_discount', 10, 2)->default(0)->nullable(false)->change();
        });
    }
};
