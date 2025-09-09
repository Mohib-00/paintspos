<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealSaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'sale_item_id',
        'deal_product_name',
        'deal_product_quantity',
        'deal_product_purchase_rate',
        'deal_product_retail_rate',
        'deal_name',
        'return_qty',
        'return_amount',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class,'sale_id', 'id');
    }

    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class,'sale_item_id', 'id');
    }
}
