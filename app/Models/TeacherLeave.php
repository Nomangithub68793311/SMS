<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherLeave extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
   
    protected $fillable=[
        'leave_name', 'name','email'
        ,'reason', 'start_date', 'finish_date','total_days','school_id'
    ];
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
