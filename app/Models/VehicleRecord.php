<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleRecord extends Model
{
    protected $fillable = [
        'vehicle_no',
        'owner_name',
        'phone_no',
        'currentoil_reading',
        'nextoil_reading',
        'oil_brand',
        'quantity',
        'gear_oil',
        'oil_filter',
        'air_filter',
        'Ac_filter',
        'battery_checkup',
        'type_air_pressure',
        'invoice_no',
        'total_bill',
        'created_at',
        'updated_at',
    ];

     public function alerts()
    {
        return $this->hasMany(AlertVehicle::class, 'vehicle_id');
    }
}
