<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    protected $fillable = [
        'customer_name', 
        'area_id', 
        'client_type', 
        'assigned_user_id', 
        'phone_1', 
        'phone_2', 
        'client_gender', 
        'client_cnic', 
        'client_father_name', 
        'client_residence', 
        'client_occupation', 
        'client_salary', 
        'client_fixed_discount', 
        'distributor_fix_margin', 
        'client_permanent_address', 
        'client_residential_address', 
        'client_office_address',
        'block',
        'img'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
