
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HostelController;
Route::post('/hostel/add/{id}',[HostelController::class,'store'])->middleware('jwt.verify');
Route::get('/hostel/all/{id}',[HostelController::class,'all'])->middleware('jwt.verify');