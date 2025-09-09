<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalaryIdToGrnAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('grn_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('salary_id')->nullable()->after('id'); 

            $table->foreign('salary_id')->references('id')->on('salaries')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('grn_accounts', function (Blueprint $table) {
            $table->dropForeign(['salary_id']);
            $table->dropColumn('salary_id');
        });
    }
}
