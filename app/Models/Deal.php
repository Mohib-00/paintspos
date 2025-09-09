<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_name',
        'deal_price',
        'remarks',
    ];

    public function dealItems()
    {
        return $this->hasMany(DealItem::class,'deal_id', 'id');
    }
}
