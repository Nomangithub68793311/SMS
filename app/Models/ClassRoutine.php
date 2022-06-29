<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoutine extends Model
{

    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $timeFormat = 'H:i:s';
    protected $fillable=[
        'teacher_name', 'id_no', 'gender', 'class'
        , 'section', 'subject','date', 'time', 'phone', 'email'
    ];

}
