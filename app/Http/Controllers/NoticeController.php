<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Notice;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Redis;

use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($id)

    {
        // $newsorder = Notice::paginate(3);

        // $news = Notice::with(['title' => function($q){
        //     $q->take(3);
        // }])->get();
        $cachedNotice = Redis::get('notice'.$id);


        if($cachedNotice) {
            $cachedNotice = json_decode($cachedNotice, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedNotice,
            ]);
        }else {
            $notice = School::find($id)->notice()->orderBy('created_at', 'desc')->get();
            Redis::set('notice'.$id, $notice);
            Redis::expire('notice'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $notice,
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
          $input = $request->only(
            'title', 'posted_by', 'details', 'post_date'
        );
    
                              

        $validator = Validator::make($input, [
            'title' => 'required',
            'posted_by' => 'required',
            'details' => 'required',
            'post_date' => 'required',
            
         
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = ['title' => $request->title, 'details' => $request->details
                      ];
        $found=School::find($id)->notice()->where($matchThese )->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'notice Exists'],422);

        }
      

        try {
            // begin transaction
            DB::beginTransaction();
            
            // write your dependent quires here
            $notice = Notice::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->notice()->save($notice);
            $notice->save();
            
            
            if (!$notice) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            return response()->json(["data"=>$notice]);
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
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function show(Notice $notice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function edit(Notice $notice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notice $notice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notice $notice)
    {
        //
    }
}
