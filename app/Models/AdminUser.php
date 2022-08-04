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
        'institution_name', 'address', 'city','hashedPassword','password',
        'zip_code', 'institution_type', 'institution_medium','country', 
        'website','phone_no','mobile_no','principal_phone_no',
        'license_copy','logo','payment_status','category','establishment_year',
        'principal_name','institution_email','principal_email','total_students',


    ];
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
