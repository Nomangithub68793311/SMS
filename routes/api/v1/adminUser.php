<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController; 
Route::post('/admin_user/add/{id}',[AdminUserController::class,'store'])->middleware('jwt.verify');
Route::post('/admin_user/login',[AdminUserController::class,'login']);

Route::get('/admin_user/all/{id}',[AdminUserController::class,'all'])->middleware('jwt.verify');