
<?php 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController; 

Route::post('/signup',[AdminController::class,'store']);
Route::post('/login',[AdminController::class,'login']);
Route::get('/data/{id}',[AccountController::class,'index'])->middleware('jwt.verify');