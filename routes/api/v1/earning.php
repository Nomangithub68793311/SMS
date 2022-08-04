
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EarningController; 
Route::post('/earning/add/{id}',[EarningController::class,'store'])->middleware('jwt.verify');
Route::get('/earning/all/{id}',[EarningController::class,'all'])->middleware('jwt.verify');
