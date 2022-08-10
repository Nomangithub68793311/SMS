<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;

use JWTAuth;
use JWTFactory;
class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($id)
    {
        $cachedbooks = Redis::get('book'.$id);


        if($cachedbooks) {
            $cachedbooks = json_decode($cachedbooks, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedbooks,
            ]);
        }else {
            $book = School::find($id)->library()->orderBy('created_at', 'desc')->get();
            Redis::set('book'.$id, $book);
            Redis::expire('book'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $book,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $input = $request->only( 'book_name', 'subject', 'writer_name', 'class'
        ,'book_id', 'publish_date', 'upload_date', );
    
                              

        $validator = Validator::make($input, [
            'book_name' => 'required',
            'subject' => 'required',
            'writer_name' => 'required',
            'class' => 'required',
            'book_id' => 'required',
            'publish_date' => 'required',
            'upload_date' => 'required'
           
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = [
            'writer_name' => $request->writer_name,
            'book_name' => $request->book_name,
            'book_id' => $request->select_code,
           ];
        $found=School::find($id)->library()->where($matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Book Exists'],422);

        }
        

        try {
            // begin transaction
            DB::beginTransaction();
            
            // write your dependent quires here
            $book = Library::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->library()->save($book);
            $book->save();
            
            if (!$book) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            return response()->json(["data"=>$book]);
        }
            catch (\Exception $e) {
            // May day,  rollback!!! rollback!!!
            DB::rollback();   
             
        return response()->json(["error"=>"didnt work"],422);

            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Library  $library
     * @return \Illuminate\Http\Response
     */
    public function show(Library $library)
    {
        $Library = Library::orderBy('created_at', 'desc')->get();
        return response()->json(["library"=>$Library]);    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Library  $library
     * @return \Illuminate\Http\Response
     */
    public function edit(Library $library)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Library  $library
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Library $library)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Library  $library
     * @return \Illuminate\Http\Response
     */
    public function destroy(Library $library)
    {
        //
    }
}
