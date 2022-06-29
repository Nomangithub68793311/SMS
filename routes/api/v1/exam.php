

<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController; 
Route::post('/exam/add',[ExamController::class,'store']);
Route::get('/all/exam',[ExamController::class,'show']);