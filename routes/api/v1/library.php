<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController; 
Route::post('/library/signup',[LibraryController::class,'store']);
Route::get('/library/signup',[LibraryController::class,'show']);