<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParentmodelController; 
Route::post('/parent/signup/{id}',[ParentmodelController::class,'store'])->middleware('jwt.verify');
Route::post('/parent/login',[ParentmodelController::class,'login']);

Route::get('/parent/all/{id}',[ParentmodelController::class,'all'])->middleware('jwt.verify');