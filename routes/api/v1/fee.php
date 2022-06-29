<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeeController; 
Route::post('/fee/add',[FeeController::class,'store']);
Route::get('/all/fee',[FeeController::class,'show']);