<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'brand_name',
        'category_name',
        'user_id',
        'image'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
