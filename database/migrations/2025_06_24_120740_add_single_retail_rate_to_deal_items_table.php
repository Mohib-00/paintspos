<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('deal_items', function (Blueprint $table) {
        $table->decimal('single_retail_rate', 15, 2)->nullable();
    });
}


    public function down()
{
    Schema::table('deal_items', function (Blueprint $table) {
        $table->dropColumn('single_retail_rate');
    });
}

};
