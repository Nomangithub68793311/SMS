

<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController; 
Route::post('/expense/add/{id}',[ExpenseController::class,'store'])->middleware('jwt.verify');
Route::get('/expense/all/{id}',[ExpenseController::class,'all'])->middleware('jwt.verify');