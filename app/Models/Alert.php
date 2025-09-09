<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = ['title', 'message', 'alert_date', 'is_read'];
}
