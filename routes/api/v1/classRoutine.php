
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassRoutineController; 
Route::post('/class_routine/add',[ClassRoutineController::class,'store']);
Route::get('/all/class',[ClassRoutineController::class,'show']);