<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;
    protected $dateFormat = 'Y-m-d';
   
    protected $fillable=[
        'title', 'posted_by', 'details', 'post_date'
       
        



    ];
}
