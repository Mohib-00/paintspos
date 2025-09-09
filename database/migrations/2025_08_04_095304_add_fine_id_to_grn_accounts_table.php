<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('grn_accounts', function (Blueprint $table) {
        $table->unsignedBigInteger('fine_id')->nullable()->after('vendor_account_id');
        $table->foreign('fine_id')->references('id')->on('fines')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('grn_accounts', function (Blueprint $table) {
        $table->dropForeign(['fine_id']);
        $table->dropColumn('fine_id');
    });
}

    
};
