<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grn_accounts', function (Blueprint $table) {
            $table->decimal('vendor_net_amount', 10, 2)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('grn_accounts', function (Blueprint $table) {
            $table->decimal('vendor_net_amount', 10, 2)->nullable(false)->change();
        });
    }
    
};
