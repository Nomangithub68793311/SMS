<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $timeFormat = 'H:i:s';
    protected $fillable=[
        'staff_id', 'name', 'gender', 'month'
        , 'amount', 'email'
    ];
    public function school()
    {
     return $this->belongsTo(School::class);
    }

}
