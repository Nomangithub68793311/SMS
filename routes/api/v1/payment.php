
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController; 
Route::post('/payment/add/{id}',[PaymentController::class,'store'])->middleware('jwt.verify');
Route::get('/attendance/all',[PaymentController::class,'show']);
