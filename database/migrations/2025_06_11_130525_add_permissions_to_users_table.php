<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->tinyInteger('user_read')->nullable()->default(1);
        $table->tinyInteger('user_add')->nullable()->default(1);
        $table->tinyInteger('user_update')->nullable()->default(1);
        $table->tinyInteger('user_delete')->nullable()->default(1);

        $table->tinyInteger('pos_read')->nullable()->default(1);
        $table->tinyInteger('pos_add')->nullable()->default(1);
        $table->tinyInteger('pos_update')->nullable()->default(1);
        $table->tinyInteger('pos_delete')->nullable()->default(1);
        $table->tinyInteger('pos_pastdate')->nullable()->default(1);

        $table->tinyInteger('sale_read')->nullable()->default(1);
        $table->tinyInteger('sale_update')->nullable()->default(1);
        $table->tinyInteger('sale_delete')->nullable()->default(1);

        $table->tinyInteger('pur_read')->nullable()->default(1);
        $table->tinyInteger('pur_add')->nullable()->default(1);
        $table->tinyInteger('pur_update')->nullable()->default(1);
        $table->tinyInteger('pur_delete')->nullable()->default(1);
        $table->tinyInteger('pur_pastdate')->nullable()->default(1);

        $table->tinyInteger('purchase_return_read')->nullable()->default(1);

        $table->tinyInteger('acc_read')->nullable()->default(1);

        $table->tinyInteger('vo_read')->nullable()->default(1);
        $table->tinyInteger('vo_add')->nullable()->default(1);
        $table->tinyInteger('vo_update')->nullable()->default(1);
        $table->tinyInteger('vo_delete')->nullable()->default(1);
        $table->tinyInteger('vo_pastdate')->nullable()->default(1);

        $table->tinyInteger('paysalary_read')->nullable()->default(1);
        $table->tinyInteger('payedsalary_read')->nullable()->default(1);

        $table->tinyInteger('reports_read')->nullable()->default(1);
        $table->tinyInteger('salereport_read')->nullable()->default(1);
        $table->tinyInteger('stockreport_read')->nullable()->default(1);
        $table->tinyInteger('dcreport_read')->nullable()->default(1);
        $table->tinyInteger('gl_read')->nullable()->default(1);

        $table->tinyInteger('vend_read')->nullable()->default(1);
        $table->tinyInteger('vend_add')->nullable()->default(1);
        $table->tinyInteger('vend_update')->nullable()->default(1);
        $table->tinyInteger('vend_delete')->nullable()->default(1);

        $table->tinyInteger('custmers_read')->nullable()->default(1);
        $table->tinyInteger('custmers_add')->nullable()->default(1);
        $table->tinyInteger('custmers_update')->nullable()->default(1);
        $table->tinyInteger('custmers_delete')->nullable()->default(1);

        $table->tinyInteger('area_read')->nullable()->default(1);
        $table->tinyInteger('area_add')->nullable()->default(1);
        $table->tinyInteger('area_update')->nullable()->default(1);
        $table->tinyInteger('area_delete')->nullable()->default(1);

        $table->tinyInteger('block_read')->nullable()->default(1);
        $table->tinyInteger('block_add')->nullable()->default(1);
        $table->tinyInteger('block_update')->nullable()->default(1);
        $table->tinyInteger('block_delete')->nullable()->default(1);

        $table->tinyInteger('empl_read')->nullable()->default(1);
        $table->tinyInteger('empl_add')->nullable()->default(1);
        $table->tinyInteger('empl_update')->nullable()->default(1);
        $table->tinyInteger('empl_delete')->nullable()->default(1);

        $table->tinyInteger('emplleave_read')->nullable()->default(1);
        $table->tinyInteger('emplleave_add')->nullable()->default(1);
        $table->tinyInteger('emplleave_update')->nullable()->default(1);
        $table->tinyInteger('emplleave_delete')->nullable()->default(1);
        $table->tinyInteger('emplleave_pastdate')->nullable()->default(1);

        $table->tinyInteger('dgnation_read')->nullable()->default(1);
        $table->tinyInteger('dgnation_add')->nullable()->default(1);
        $table->tinyInteger('dgnation_update')->nullable()->default(1);
        $table->tinyInteger('dgnation_delete')->nullable()->default(1);
        $table->tinyInteger('dgnation_pastdate')->nullable()->default(1);

        $table->tinyInteger('atndnce_read')->nullable()->default(1);

        $table->tinyInteger('atndncereport_read')->nullable()->default(1);

        $table->tinyInteger('cmppny_read')->nullable()->default(1);
        $table->tinyInteger('cmppny_add')->nullable()->default(1);
        $table->tinyInteger('cmppny_update')->nullable()->default(1);
        $table->tinyInteger('cmppny_delete')->nullable()->default(1);

        $table->tinyInteger('ctgry_read')->nullable()->default(1);
        $table->tinyInteger('ctgry_add')->nullable()->default(1);
        $table->tinyInteger('ctgry_update')->nullable()->default(1);
        $table->tinyInteger('ctgry_delete')->nullable()->default(1);

        $table->tinyInteger('subctgry_read')->nullable()->default(1);
        $table->tinyInteger('subctgry_add')->nullable()->default(1);
        $table->tinyInteger('subctgry_update')->nullable()->default(1);
        $table->tinyInteger('subctgry_delete')->nullable()->default(1);

        $table->tinyInteger('product_read')->nullable()->default(1);
        $table->tinyInteger('product_add')->nullable()->default(1);
        $table->tinyInteger('product_update')->nullable()->default(1);
        $table->tinyInteger('product_delete')->nullable()->default(1);

        $table->tinyInteger('productprice_read')->nullable()->default(1);

        $table->tinyInteger('productimport_read')->nullable()->default(1);
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'user_read', 'user_add', 'user_update', 'user_delete',
            'pos_read', 'pos_add', 'pos_update', 'pos_delete', 'pos_pastdate',
            'sale_read', 'sale_update', 'sale_delete',
            'pur_read', 'pur_add', 'pur_update', 'pur_delete', 'pur_pastdate',
            'purchase_return_read',
            'acc_read',
            'vo_read', 'vo_add', 'vo_update', 'vo_delete', 'vo_pastdate',
            'paysalary_read', 'payedsalary_read',
            'reports_read', 'salereport_read', 'stockreport_read', 'dcreport_read', 'gl_read',
            'vend_read', 'vend_add', 'vend_update', 'vend_delete',
            'custmers_read', 'custmers_add', 'custmers_update', 'custmers_delete',
            'area_read', 'area_add', 'area_update', 'area_delete',
            'block_read', 'block_add', 'block_update', 'block_delete',
            'empl_read', 'empl_add', 'empl_update', 'empl_delete',
            'emplleave_read', 'emplleave_add', 'emplleave_update', 'emplleave_delete', 'emplleave_pastdate',
            'dgnation_read', 'dgnation_add', 'dgnation_update', 'dgnation_delete', 'dgnation_pastdate',
            'atndnce_read',
            'atndncereport_read',
            'cmppny_read', 'cmppny_add', 'cmppny_update', 'cmppny_delete',
            'ctgry_read', 'ctgry_add', 'ctgry_update', 'ctgry_delete',
            'subctgry_read', 'subctgry_add', 'subctgry_update', 'subctgry_delete',
            'product_read', 'product_add', 'product_update', 'product_delete',
            'productprice_read',
            'productimport_read'
        ]);
    });
}

};
