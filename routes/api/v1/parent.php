<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParentmodelController; 
Route::post('/parent/signup/{id}',[ParentmodelController::class,'store'])->middleware('jwt.verify');
Route::post('/parent/login',[ParentmodelController::class,'login']);
Route::get('/parent/personal/data/{id}',[ParentmodelController::class,'data'])->middleware('jwt.parent');

Route::post('/parent/delete/{id}',[ParentmodelController::class,'delete'])->middleware('jwt.verify');
Route::post('/parent/update/{id}',[ParentmodelController::class,'update'])->middleware('jwt.verify');

Route::get('/parent/all/{id}',[ParentmodelController::class,'all'])->middleware('jwt.verify');