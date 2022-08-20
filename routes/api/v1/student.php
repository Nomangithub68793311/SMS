<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController; 
Route::post('/student/signup/{id}',[StudentController::class,'store'])->middleware('jwt.verify');
Route::post('/student/login',[StudentController::class,'login']);
Route::get('/student/personal/data/{id}/{identity}',[StudentController::class,'personalData'])->middleware('jwt.student');
Route::get('/student/class_routine/{id}/{identity}',[StudentController::class,'classRoutine'])->middleware('jwt.student');
Route::get('/student/exam/{id}/{identity}',[StudentController::class,'exam'])->middleware('jwt.student');
Route::get('/student/fee/{id}/{identity}',[StudentController::class,'fee'])->middleware('jwt.student');

Route::post('/student/delete/{id}',[StudentController::class,'delete'])->middleware('jwt.verify');
Route::post('/student/update/{id}',[StudentController::class,'update'])->middleware('jwt.verify');

Route::get('/student/all/{id}',[StudentController::class,'all'])->middleware('jwt.verify');
Route::get('/student/check',[StudentController::class,'check']);

Route::get('/student/all/get',[StudentController::class,'all']);

