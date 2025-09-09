<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_name',
        'category_name',
        'item_name',
        'purchase_rate',
        'quantity',
    ];
}

