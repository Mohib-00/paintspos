<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'designation_name',
        'address',
        'phone',
        'image',
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
