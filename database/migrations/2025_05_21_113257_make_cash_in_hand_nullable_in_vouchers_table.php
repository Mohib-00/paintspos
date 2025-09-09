<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->decimal('cash_in_hand', 15, 2)->nullable()->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->decimal('cash_in_hand', 15, 2)->default(0)->nullable(false)->change();
        });
    }
};
