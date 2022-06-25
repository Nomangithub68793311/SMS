<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController; 
Route::post('/subject/signup',[SubjectController::class,'store']);
Route::get('/subject/signup',[SubjectController::class,'show']);