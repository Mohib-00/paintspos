<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('vouchers', function (Blueprint $table) {
        $table->string('jv')->nullable();
        $table->string('status')->nullable();
    });

    Schema::table('voucher_items', function (Blueprint $table) {
        $table->string('jv')->nullable();
        $table->string('status')->nullable();
    });
}

public function down(): void
{
    Schema::table('vouchers', function (Blueprint $table) {
        $table->dropColumn(['jv', 'status']);
    });

    Schema::table('voucher_items', function (Blueprint $table) {
        $table->dropColumn(['jv', 'status']);
    });
}

};
