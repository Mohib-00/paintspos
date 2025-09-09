<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->string('payment_status')->default('pending');
        $table->string('payment_method')->nullable();
        $table->string('bank_name')->nullable();
        $table->decimal('amount_payed', 10, 2)->nullable();
        $table->decimal('amount_remain', 10, 2)->nullable();
    });
}

public function down()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->dropColumn(['payment_status', 'payment_method', 'bank_name', 'amount_payed', 'amount_remain']);
    });
}

};
