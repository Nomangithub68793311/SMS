

<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController; 
Route::post('/expense/add',[ExpenseController::class,'store']);
Route::get('/expense/all',[ExpenseController::class,'show']);