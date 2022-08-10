<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherLeaveController; 
Route::post('/teacher_leave/add',[TeacherLeaveController::class,'store']);




