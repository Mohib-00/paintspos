<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('deal_sale_items', function (Blueprint $table) {
        $table->string('deal_name')->nullable()->after('sale_item_id'); 
    });
}

public function down()
{
    Schema::table('deal_sale_items', function (Blueprint $table) {
        $table->dropColumn('deal_name');
    });
}

};
