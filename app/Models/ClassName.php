<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ClassName extends Model
{
    use HasFactory;
    protected $timeFormat = 'H:i:s';
    protected $dateFormat = 'Y-m-d';

    // $dateFormat = 'H:i:s'; 
    // protected $dates = [ 'time', ];
    protected $fillable=[
        'teacher_name', 'gender', 'class', 'id_no'
        ,'phone', 'subject', 'section' ,'email', 'date', 'time',
        



    ];

}
