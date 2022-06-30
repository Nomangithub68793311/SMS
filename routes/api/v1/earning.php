
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EarningController; 
Route::post('/earning/add',[EarningController::class,'store']);
Route::get('/earning/all',[EarningController::class,'show']);
