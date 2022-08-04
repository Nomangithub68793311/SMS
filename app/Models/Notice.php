<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable=[
        'title', 'posted_by', 'details', 'post_date'
    ];
    public function school()
    {
     return $this->belongsTo(School::class);
    }

     	 	
//     public function getCreatedAtAttribute($date)
// {
//     $this->attributes['created_at'] = Carbon::createFromFormat('d-m-Y H:i:s',$date);
// }

// public function getUpdatedAtAttribute($date)
// {
//     $this->attributes['updated_at'] = Carbon::createFromFormat('d-m-Y H:i:s',$date);
// }
}
