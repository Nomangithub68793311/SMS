<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable=[
        'session_from', 'session_to', 'select_month_from', 'select_month_to' ,
        'select_class', 'select_section'
      
        



    ];
}
