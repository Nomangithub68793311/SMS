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
        'first_name', 'last_name', 'gender','user_name','role',
        'zip_code', 'hashedPassword', 'password','joining_date', 
        'email','phone','school_id','address'
       


    ];
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
