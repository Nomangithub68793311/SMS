<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController; 
Route::post('/teacher/signup/{id}',[TeacherController::class,'store'])->middleware('jwt.verify');
Route::post('/teacher/login',[TeacherController::class,'login']);
Route::get('/teacher/personal/data/{id}/{identity}',[TeacherController::class,'personalData'])->middleware('jwt.teacher');
Route::get('/teacher/class_routine/{id}/{identity}',[TeacherController::class,'classRoutine'])->middleware('jwt.teacher');
Route::get('/teacher/exam/{id}/{identity}',[TeacherController::class,'exam'])->middleware('jwt.teacher');
Route::get('/teacher/salary/{id}/{identity}',[TeacherController::class,'exam'])->middleware('jwt.teacher');
Route::get('/teacher/fee/{id}/{identity}',[TeacherController::class,'fee'])->middleware('jwt.teacher');


Route::get('/teacher/all/{id}',[TeacherController::class,'all'])->middleware('jwt.verify');