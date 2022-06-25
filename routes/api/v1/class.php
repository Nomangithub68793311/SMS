
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassNameController; 
Route::post('/classname/signup',[ClassNameController::class,'store']);
Route::get('/classname/signup',[ClassNameController::class,'show']);