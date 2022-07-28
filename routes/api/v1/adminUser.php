<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController; 
Route::post('/admin_user/add',[AdminUserController::class,'store']);
Route::post('/adminUser/login',[AdminUserController::class,'login']);

Route::get('/admin/all',[AdminUserController::class,'show']);
Route::get('/date/diff/{date1}/{date2}',[AdminUserController::class,'create']);



