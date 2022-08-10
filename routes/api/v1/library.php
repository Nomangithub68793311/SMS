<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController; 
Route::post('/library/add/{id}',[LibraryController::class,'store'])->middleware('jwt.verify');
Route::get('/library/all/{id}',[LibraryController::class,'all'])->middleware('jwt.verify');