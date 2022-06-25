
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassRoutineController; 
Route::post('/classroutine/signup',[ClassRoutineController::class,'store']);
Route::get('/classroutine/signup',[ClassRoutineController::class,'show']);