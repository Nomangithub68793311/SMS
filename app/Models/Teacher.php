<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $dateFormat = 'Y-m-d';
    protected $hidden = [
        'password','hashedPassword'
        
    ];
    protected $fillable=[
        'first_name', 'last_name','gender', 'date_of_birth', 'id_no',
        'blood_group', 'religion', 'email','class', 'section',
        'phone','address','bio','password','hashedPassword'



    ];
}
