<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grn_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_id')->nullable()->after('vendor_account_id');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('grn_accounts', function (Blueprint $table) {
            $table->dropForeign(['purchase_id']);
            $table->dropColumn('purchase_id');
        });
    }
    
};
