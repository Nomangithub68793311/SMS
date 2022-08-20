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
      'hashedPassword'
        
    ];
    protected $fillable=[
        'first_name', 'last_name','gender', 'date_of_birth', 'roll','parentmodel_id',
        'blood_group', 'religion', 'email','class', 'section', 'admission_id',
        'phone','address','bio','password','hashedPassword','admitted_year',
        'testimonial','certificate','signature','marksheet','photo','school_id'


    ];
    protected static function booted()
    {
        static::creating(function (Model $model) {
            $model->admitted_year = $model->freshTimestamp();
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function parentmodel()
    {
        return $this->belongsTo(Parentmodel::class);
    }
    public function studentLeave()
    {
        return $this->hasMany(StudentLeave::class);
    }
}
