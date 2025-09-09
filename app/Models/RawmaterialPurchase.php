<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RawmaterialPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiving_location',
        'vendors',
        'invoice_no',
        'created_at',
        'updated_at',
        'remarks',
        'single_purchase_rate',
        'products',
        'quantity',
        'purchase_rate',
        'totalquantity',
        'gross_amount',
        'discount',
        'net_amount',
        'stock_status',
    ];

    protected $casts = [
        'single_purchase_rate' => 'array',
        'products' => 'array',
        'quantity' => 'array',
        'purchase_rate' => 'array',
    ];



       public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function products()
{
    return $this->belongsToMany(Product::class); 
}

public function vendorAccount()
{
    return $this->belongsTo(AddAccount::class, 'vendors', 'sub_head_name');
}



public function saleTrackings()
{
    return $this->hasMany(SalePurchaseTracking::class, 'purchase_id');
}

public function grnAccounts()
{
    return $this->hasMany(GrnAccount::class, 'raw_material_purchase_id');
}


}

