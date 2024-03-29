<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
   
    protected $fillable=[
        'book_name', 'subject', 'writer_name', 'class'
        ,'book_id', 'publish_date', 'upload_date','school_id'
    ];
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
