<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertVehicle extends Model
{
    protected $fillable = ['vehicle_id', 'alert', 'created_at', 'updated_at'];

    public function vehicle()
    {
        return $this->belongsTo(VehicleRecord::class, 'vehicle_id');
    }
}
