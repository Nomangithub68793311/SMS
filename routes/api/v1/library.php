<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController; 
Route::post('/library/add',[LibraryController::class,'store']);
Route::get('/library/all',[LibraryController::class,'show']);