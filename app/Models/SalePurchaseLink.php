<?php
// app/Models/SalePurchaseLink.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePurchaseLink extends Model
{
    protected $fillable = [
        'sale_id',
        'sale_item_id',
        'purchase_id',
        'product_id',
        'deducted_quantity',
        'deducted_purchase_rate',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class,'sale_item_id', 'id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class,'purchase_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id', 'id');
    }
}
