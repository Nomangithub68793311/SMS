<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;
    protected $timeFormat = 'H:i:s';
    protected $dateFormat = 'Y-m-d';
    protected $fillable=[
        'route_name', 'vehicle_number', 'license_number', 'select_class'
        , 'phone_number', 'driver_name'
    ];
}
