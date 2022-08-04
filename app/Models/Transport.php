<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $timeFormat = 'H:i:s';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable=[
        'route_name', 'vehicle_number', 'license_number'
        , 'phone_number', 'driver_name'
    ];
    public function school()
    {
     return $this->belongsTo(School::class);
    }
}
