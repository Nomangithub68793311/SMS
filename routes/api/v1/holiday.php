
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HolidayController; 
Route::post('/holiday/add/{id}',[HolidayController::class,'store'])->middleware('jwt.verify');
Route::get('/all/holiday/{id]',[HolidayController::class,'all'])->middleware('jwt.verify');