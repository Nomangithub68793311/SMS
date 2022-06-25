
<?php 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController; 

Route::post('/signup',[AdminController::class,'store']);
Route::post('/login',[AdminController::class,'login']);
Route::get('/data/all',[AdminController::class,'all']);
Route::get('/data/{id}',[AdminController::class,'index'])->middleware('jwt.verify');