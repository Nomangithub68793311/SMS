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
    public function student()
    {
        return $this->hasMany(Student::class);
    }


    public function teacher()
    {
        return $this->hasMany(Teacher::class);
    }
    


    public function parentmodel()
   {
    return $this->hasMany(Parentmodel::class);
   }
   public function notice()
   {
    return $this->hasMany(Notice::class);
   }
   public function expense()
   {
    return $this->hasMany(Expense::class);
   }
   public function fee()
   {
    return $this->hasMany(Fee::class);
   }
   public function salary()
   {
    return $this->hasMany(Salary::class);
   }
   public function earning()
   {
    return $this->hasMany(Earning::class);
   }
   public function subject()
   {
    return $this->hasMany(Subject::class);
   }
   public function transport()
   {
    return $this->hasMany(Transport::class);
   }
   public function hostel()
   {
    return $this->hasMany(Hostel::class);
   }
   public function adminUser()
   {
    return $this->hasMany(AdminUser::class); 
   }
   public function classRoutine()
   {
    return $this->hasMany(ClassRoutine::class);
   }
   public function holiday()
   {
    return $this->hasMany(Holiday::class);
   }
    
    
    

}
