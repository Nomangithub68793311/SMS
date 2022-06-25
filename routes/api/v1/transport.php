
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransportController; 
Route::post('/transport/signup',[TransportController::class,'store']);
Route::get('/transport/signup',[TransportController::class,'show']);