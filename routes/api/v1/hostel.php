
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HostelController; 
Route::post('/hostel/add',[HostelController::class,'store']);
Route::get('/hostel/all',[HostelController::class,'show']);