<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $hidden = [
        'password','hashedPassword'
        
    ];
    protected $fillable=[
        'first_name', 'last_name','gender', 'date_of_birth', 'roll',
        'blood_group', 'religion', 'email','class', 'section', 'admission_id',
        'phone','address','bio','password','hashedPassword','admitted_year',
        'testimonial','certificate','signature','marksheet','photo','admin_id'


    ];
    protected static function booted()
    {
        static::creating(function (Model $model) {
            $model->admitted_year = $model->freshTimestamp();
        });
    }

    // public function school()
    // {
    //     return $this->belongsTo(School::class);
    // }
}
