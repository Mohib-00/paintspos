<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    // Define fillable fields
    protected $fillable = [
        'user_id',  
        'status',   
    ];

    public function employee()
    {
        return $this->belongsTo(emplyees::class, 'user_id');
    }
}
