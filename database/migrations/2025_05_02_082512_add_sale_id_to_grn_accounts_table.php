<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('grn_accounts', function (Blueprint $table) {
        $table->unsignedBigInteger('sale_id')->nullable()->after('vendor_account_id');
        $table->foreign('sale_id')->references('id')->on('sales')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('grn_accounts', function (Blueprint $table) {
        $table->dropForeign(['sale_id']);
        $table->dropColumn('sale_id');
    });
}

};
