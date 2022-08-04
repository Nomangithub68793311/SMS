<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    use HasFactory;

    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $timeFormat = 'H:i:s';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable=[
        'name', 'amount', 'type', 'date'
    ];
    public function school()
    {
     return $this->belongsTo(School::class);
    }
}
