
<?php 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController; 

Route::post('/signup',[AdminController::class,'store']);
Route::post('admin/login',[AdminController::class,'login']);
Route::get('/data/all/{id}',[AdminController::class,'all'])->middleware('jwt.verify');
Route::get('/data/{id}',[AdminController::class,'index'])->middleware('jwt.verify');
Route::get('/admin/logout',[AdminController::class,'logout'])->middleware('jwt.verify');




