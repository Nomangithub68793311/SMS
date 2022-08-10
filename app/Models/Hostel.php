<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable=[
        'hostel_name', 'room_number', 'room_type'
        , 'num_of_bed', 'cost_per_bed','school_id'
    ];
    public function school()
    {
     return $this->belongsTo(School::class);
    }

}
