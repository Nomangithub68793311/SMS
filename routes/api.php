<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::fallback(function () {
    return response()->json(["error"=>"Route does not match"]);
});

Route::prefix('v1')
    ->group(function(){
   $dirIterator=new RecursiveDirectoryIterator( __DIR__ . '/api/v1');
   $it=new RecursiveIteratorIterator($dirIterator);
            while($it->valid()){
                if(    !$it->isDot()
                    && $it->isFile()
                    && $it->isReadable()
                    && $it->current()->getExtension() === 'php'
                    )
                    {
                        require $it->key();
                        require $it->current()->getPathname();

                    }
                    $it->next();
                                }
                     });




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
