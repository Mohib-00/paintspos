<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = [
        'employee_id',
        'narration',
        'fine',
        'created_at',
        'updated_at',
    ];

    public function employee()
    {
        return $this->belongsTo(emplyees::class, 'employee_id');
    }

    public function grnAccount()
{
    return $this->hasOne(GrnAccount::class, 'fine_id');
}

}
