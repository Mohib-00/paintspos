<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_name',
        'category_name',
        'subcategory_name',
        'item_name',
        'barcode',
        'purchase_rate',
        'retail_rate',
        'single_purchase_rate',
        'single_retail_rate',
        'quantity',
        'user_id',
        'opening_quantity',
        'image',
        'shade',
        'code'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function salePurchaseTrackings()
{
    return $this->hasMany(SalePurchaseTracking::class, 'product_id');
}

public function salePurchaseLinks()
{
    return $this->hasMany(SalePurchaseLink::class);
}

}

