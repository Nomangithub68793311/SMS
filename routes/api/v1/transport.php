
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransportController; 
Route::post('/transport/add/{id}',[TransportController::class,'store'])->middleware('jwt.verify');
Route::get('/transport/all/{id}',[TransportController::class,'all'])->middleware('jwt.verify');