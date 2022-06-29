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
        'hostel_name', 'room_number', 'room_type'
        , 'num_of_bed', 'cost_per_bed'
    ];
}
