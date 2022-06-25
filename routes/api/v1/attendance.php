
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController; 
Route::post('/attendance/signup',[AttendanceController::class,'store']);
Route::get('/attendance/signup',[AttendanceController::class,'show']);