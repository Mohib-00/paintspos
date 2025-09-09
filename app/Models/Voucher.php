<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiving_location',
        'voucher_type',
        'cash_in_hand',
        'totalAmount',
        'remarks',
        'voucher_status',
        'jv',
        'status',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function voucherItems()
    {
        return $this->hasMany(VoucherItem::class,'voucher_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function grnAccounts()
{
    return $this->hasMany(GrnAccount::class, 'voucher_id', 'id');
}


  

    
}
