<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('grn_accounts', function (Blueprint $table) {
        $table->string('payment')->nullable();
        $table->string('grn')->nullable();
    });
}

public function down()
{
    Schema::table('grn_accounts', function (Blueprint $table) {
        $table->dropColumn('payment');
        $table->dropColumn('grn');
    });
}

};
