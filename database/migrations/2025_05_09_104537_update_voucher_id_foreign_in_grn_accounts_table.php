<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grn_accounts', function (Blueprint $table) {
            // Drop the existing foreign key first
            $table->dropForeign(['voucher_id']);

            // Add the new foreign key constraint
            $table->foreign('voucher_id')
                ->references('id')
                ->on('vouchers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('grn_accounts', function (Blueprint $table) {
            // Revert the change
            $table->dropForeign(['voucher_id']);

            // Optionally, you can re-add the previous foreign key constraint
            $table->foreign('voucher_id')
                ->references('id')
                ->on('voucher_items')
                ->onDelete('cascade');
        });
    }
};
