<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController; 
Route::post('/notice/add',[NoticeController::class,'store']);
Route::get('/notice/all',[NoticeController::class,'index']);