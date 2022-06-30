
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassNameController; 
Route::post('/class/add',[ClassNameController::class,'store']);
Route::get('/class/all',[ClassNameController::class,'show']);