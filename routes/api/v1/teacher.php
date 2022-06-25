<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController; 
Route::post('/teacher/signup',[TeacherController::class,'store']);
Route::get('/teacher/signup',[TeacherController::class,'show']);