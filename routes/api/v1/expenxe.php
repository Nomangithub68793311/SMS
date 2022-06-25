

<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController; 
Route::post('/expense/signup',[ExpenseController::class,'store']);
Route::get('/expense/signup',[ExpenseController::class,'show']);