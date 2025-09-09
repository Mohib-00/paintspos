<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id',
        'products',
        'quantity',
        'single_purchase_rate',
        'single_retail_rate',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id', 'id');
    }
}
