<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController; 
Route::post('/subject/add',[SubjectController::class,'store']);
Route::get('/subject/all',[SubjectController::class,'show']);