<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand_name',
        'category_name',
        'image',
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
