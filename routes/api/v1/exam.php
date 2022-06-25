

<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController; 
Route::post('/exam/signup',[ExamController::class,'store']);
Route::get('/exam/signup',[ExamController::class,'show']);