<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $timeFormat = 'H:i:s';
    protected $dateFormat = 'Y-m-d H:i:s';

   
    protected $fillable=[
        'subject_name', 'subject_type', 'select_class', 'select_code','school_id'
    ];
    public function school()
    {
     return $this->belongsTo(School::class);
    }
}
