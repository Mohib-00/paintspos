<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('grn_accounts', function (Blueprint $table) {
        $table->unsignedBigInteger('raw_material_purchase_id')->nullable()->after('purchase_id');

        // Add foreign key constraint
        $table->foreign('raw_material_purchase_id')
              ->references('id')
              ->on('rawmaterial_purchases')
              ->onDelete('set null');
    });
}

   public function down()
{
    Schema::table('grn_accounts', function (Blueprint $table) {
        $table->dropForeign(['raw_material_purchase_id']);
        $table->dropColumn('raw_material_purchase_id');
    });
}

};
