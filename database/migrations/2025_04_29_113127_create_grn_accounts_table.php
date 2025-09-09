<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grn_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_account_id'); 
            $table->decimal('vendor_net_amount', 10, 2);
            $table->timestamps();
    
            $table->foreign('vendor_account_id')->references('id')->on('add_accounts')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grn_accounts');
    }
};
