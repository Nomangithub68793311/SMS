<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController; 
Route::post('/notice/add/{id}',[NoticeController::class,'store'])->middleware('jwt.verify');
Route::get('/notice/all/{id}',[NoticeController::class,'all'])->middleware('jwt.verify');