<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $hidden = [
        'password','hashedPassword'
        
    ];
    protected $fillable=[
        'first_name', 'last_name','gender', 'date_of_birth',
        'fathers_name', 'religion', 'email','mothers_name', 'joining_date',
        'phone','address','id_no','password','hashedPassword'


    ];
}
