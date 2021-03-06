<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController; 
Route::post('/admin_signup/add',[SchoolController::class,'store']);
Route::post('/admin_login/permission',[SchoolController::class,'permission']);
Route::get('/admin_signup/all',[SchoolController::class,'all']);
Route::get('/date/diff/{date1}/{date2}',[SchoolController::class,'create']);



