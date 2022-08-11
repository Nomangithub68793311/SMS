<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentLeaveController;
Route::post('/student_leave/add',[StudentLeaveController::class,'store']);




