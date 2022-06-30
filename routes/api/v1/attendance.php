
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController; 
Route::post('/attendance/add',[AttendanceController::class,'store']);
Route::get('/attendance/all',[AttendanceController::class,'show']);
Route::get('/attendance/class/{day}/{class}/{section}',[AttendanceController::class,'getData']);
