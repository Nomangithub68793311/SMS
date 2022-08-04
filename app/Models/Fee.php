<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $timeFormat = 'H:i:s';
    protected $fillable=[
        'class', 'section', 'fee_name', 'fee_amount'
        , 'fee_type', 'starts_from','finishes_at', 
    ];
    public function school()
    {
     return $this->belongsTo(School::class);
    }
}
