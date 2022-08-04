<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController; 
Route::post('/subject/add/{id}',[SubjectController::class,'store'])->middleware('jwt.verify');
Route::get('/subject/all/{id}',[SubjectController::class,'all'])->middleware('jwt.verify');