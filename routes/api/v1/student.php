<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController; 
Route::post('/student/signup/{id}',[StudentController::class,'store'])->middleware('jwt.verify');
Route::post('/student/login',[StudentController::class,'login']);

Route::get('/student/all/{id}',[StudentController::class,'all'])->middleware('jwt.verify');
Route::get('/student/check',[StudentController::class,'check']);

Route::get('/student/all/get',[StudentController::class,'all']);

