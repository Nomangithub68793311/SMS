<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalaryController; 
Route::post('/salary/add',[SalaryController::class,'store']);
Route::get('/salary/all',[SalaryController::class,'show']);