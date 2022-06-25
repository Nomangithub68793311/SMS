<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $dateFormat = 'Y-m-d';
   
    protected $fillable=[
        'name', 'id_no', 'expense_type', 'amount',"date",'phone'
       , 'email', 'status',
        



    ];
}
