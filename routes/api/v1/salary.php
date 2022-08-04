<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalaryController; 
Route::post('/salary/add/{id}',[SalaryController::class,'store'])->middleware('jwt.verify');
Route::get('/salary/all/{id}',[SalaryController::class,'all'])->middleware('jwt.verify');