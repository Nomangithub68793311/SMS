<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatabaseInfo; 
Route::get('/memory/kb',[DatabaseInfo::class,'totalMemorygetDBSizeInKB']);
Route::get('/memory/mb',[DatabaseInfo::class,'eachMemorygetDBSizeInMB']);