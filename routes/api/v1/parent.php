<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParentmodelController; 
Route::post('/parent/signup',[ParentmodelController::class,'store']);
Route::get('/parent/all',[ParentmodelController::class,'show']);