<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeeController; 
Route::post('/fee/add/{id}',[FeeController::class,'store'])->middleware('jwt.verify');
Route::get('/fee/all/{id}',[FeeController::class,'all'])->middleware('jwt.verify');