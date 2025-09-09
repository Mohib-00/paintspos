<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id', 'product_name', 'product_quantity', 'product_rate', 'product_subtotal','purchase_rate','created_at','updated_at','return_qty','return_amount','deal_items',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class,'sale_id', 'id');
    }

    public function product()
{
    return $this->belongsTo(Product::class);
}


public function dealSaleItems()
{
    return $this->hasMany(DealSaleItem::class, 'sale_item_id','id');
}



public function purchaseTrackings()
{
    return $this->hasMany(SalePurchaseTracking::class, 'sale_item_id');
}

public function purchaseLinks()
{
    return $this->hasMany(SalePurchaseLink::class);
}



}

