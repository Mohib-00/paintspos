<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePurchaseTracking extends Model
{
    use HasFactory;
      protected $table = 'sale_purchase_tracking';

    protected $fillable = [
        'sale_item_id',
        'purchase_id',
        'product_id',
        'quantity_deducted',
        'rate_deducted',
    ];

    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class, 'sale_item_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
