<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController; 
Route::post('/student/signup',[StudentController::class,'store']);
Route::get('/student/signup/{dt}',[StudentController::class,'show']);


// {"first_name": "fg",
//     "last_name": "fdg",
//     "gender": "dfd",
//     "date_of_birth": "2022-05-22",
//     "roll": 23,
//     "blood_group": "a+",
//     "religion": "islam",
//     "email": "rabhjs@aol.com",
//     "class": "one",
//     "section": "r",
//     "admission_id": 236,
//     "phone": 5668464,
//     "address": "dsfd",
//     "bio": "dfsdfd"
//     }