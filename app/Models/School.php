<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $hidden = [
        'password','hashedPassword'
        
    ];
    protected $fillable=[
        'institution_name', 'address','gender', 'city',
        'zip_code', 'institution_type', 'institution_medium','country', 'category',
        'website','phone_no','mobile_no','principal_phone_no','establishment_year',
        'logo','license_copy','payment_date','payment_status','login_permitted',
        'principal_name','institution_email','principal_email','total_students','hashedPassword','password'
    ];
    // public function students()
    // {
    //     return $this->hasMany(Student::class);
    // }


// //     public function teachers()
//     {
//         return $this->hasMany(Teacher::class);
//     }


//     public function parentmodels()
//    {
//     return $this->hasMany(Parentmodel::class);
//    }
    

}
