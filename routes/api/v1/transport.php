
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransportController; 
Route::post('/transport/add',[TransportController::class,'store']);
Route::get('/transport/all',[TransportController::class,'show']);