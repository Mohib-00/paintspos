<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->decimal('amount_payed_return', 15, 2)->nullable()->after('amount_payed');
    });
}


   public function down()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->dropColumn('amount_payed_return');
    });
}

};
