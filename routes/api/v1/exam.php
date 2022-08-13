

<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController; 
Route::post('/exam/add/{id}',[ExamController::class,'store']);
Route::get('/exam/all/{id}',[ExamController::class,'all']);