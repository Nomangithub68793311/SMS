<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
   
    protected $fillable=[
        'name', 'id_no', 'expense_type', 'amount',"date",'phone'
       , 'email', 'status','school_id'
    ];
    public function school()
    {
     return $this->belongsTo(School::class);
    }
}
