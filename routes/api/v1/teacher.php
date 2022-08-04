<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController; 
Route::post('/teacher/signup/{id}',[TeacherController::class,'store'])->middleware('jwt.verify');
Route::post('/teacher/login',[TeacherController::class,'login']);

Route::get('/teacher/all/{id}',[TeacherController::class,'all'])->middleware('jwt.verify');