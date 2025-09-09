<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiving_location',
        'vendors',
        'invoice_no',
        'remarks',
        'products',
        'quantity',
        'purchase_rate',
        'retail_rate',
        'single_purchase_rate',
        'single_retail_rate',
        'totalquantity',
        'gross_amount',
        'discount',
        'net_amount',
        'created_at',
        'payment_status',
        'payment_method',
        'bank_name',
        'amount_payed',
        'amount_remain',
        'updated_at',
        'original_qty'
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

public function grnAccounts()
{
    return $this->hasMany(GrnAccount::class, 'purchase_id');
}


public function saleTrackings()
{
    return $this->hasMany(SalePurchaseTracking::class, 'purchase_id');
}

public function salePurchaseLinks()
{
    return $this->hasMany(SalePurchaseLink::class);
}



}
