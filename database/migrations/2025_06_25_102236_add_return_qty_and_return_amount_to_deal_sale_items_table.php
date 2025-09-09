<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnQtyAndReturnAmountToDealSaleItemsTable extends Migration
{
    public function up()
    {
        Schema::table('deal_sale_items', function (Blueprint $table) {
            $table->integer('return_qty')->nullable();
            $table->decimal('return_amount', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('deal_sale_items', function (Blueprint $table) {
            $table->dropColumn(['return_qty', 'return_amount']);
        });
    }
}
