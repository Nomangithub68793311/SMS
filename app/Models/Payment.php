<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $timeFormat = 'H:i:s';
    protected $fillable=[
        'amount', 'payment_date',
        'school_id'
    ];



    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
