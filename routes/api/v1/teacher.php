<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController; 
Route::post('/teacher/signup',[TeacherController::class,'store']);
Route::post('/teacher/login',[TeacherController::class,'login']);

Route::get('/teacher/all',[TeacherController::class,'show']);