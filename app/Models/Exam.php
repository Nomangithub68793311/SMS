<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $timeFormat = 'H:i:s';
    protected $dateFormat = 'Y-m-d';
    protected $fillable=[
        'exam_name', 'select_date', 'subject_type', 'select_class'
        , 'select_section', 'select_time'
    ];
}
