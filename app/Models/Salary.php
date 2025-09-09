<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'paid',
        'bonus',
        'remakrs'
    ];

    public function employee()
    {
        return $this->belongsTo(emplyees::class, 'employee_id', 'id');
    }

    public function grnAccounts()
{
    return $this->hasMany(GrnAccount::class, 'salary_id');
}

}

