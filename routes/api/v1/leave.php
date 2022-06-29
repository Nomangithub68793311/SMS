

<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaveController; 
Route::post('/leave/add',[LeaveController::class,'store']);
Route::get('/all/leave',[LeaveController::class,'show']);