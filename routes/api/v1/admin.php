
<?php 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController; 

Route::post('/signup',[AdminController::class,'store']);

Route::post('super_admin/login',[AdminController::class,'superAdminlogin']);
Route::post('admin/login',[AdminController::class,'adminlogin']);


Route::get('/data/super_admin/all/{id}',[AdminController::class,'forSuperAdmin'])->middleware('jwt.verify');
Route::get('/data/admin/all/{id}',[AdminController::class,'forAdmin'])->middleware('jwt.admin');

Route::get('/data/{id}',[AdminController::class,'index'])->middleware('jwt.verify');
Route::get('/admin/logout',[AdminController::class,'logout'])->middleware('jwt.verify');




