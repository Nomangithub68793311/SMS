<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController; 
Route::post('/notice/signup',[NoticeController::class,'store']);
Route::get('/notice/signup',[NoticeController::class,'show']);