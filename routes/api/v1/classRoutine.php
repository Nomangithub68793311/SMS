
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassRoutineController; 
Route::post('/class_routine/add/{id}',[ClassRoutineController::class,'store'])->middleware('jwt.verify');
Route::get('/class_routine/all/{id}',[ClassRoutineController::class,'all'])->middleware('jwt.verify');