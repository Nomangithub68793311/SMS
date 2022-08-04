<?php

namespace App\Http\Controllers;

use App\Models\Earning;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\School;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;

class EarningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($id)
    {
        $cachedEarning = Redis::get('earning'.$id);


        if($cachedEarning) {
            $cachedEarning = json_decode($cachedEarning, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedEarning,
            ]);
        }else {
            $earning = School::find($id)->earning()->orderBy('created_at', 'desc')->get();
            Redis::set('earning'.$id, $earning);
            Redis::expire('earning'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $earning,
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
            'name', 'amount', 'type', 'date'
        );
    
                              

        $validator = Validator::make($input, [
            'name' => 'required',
            'amount' => 'required',
            'type' => 'required',
            'date' => 'required'
            
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'invalid data']);

        }

        $matchThese = ['name' => $request->name ];
       $found=School::find($id)->earning()->where($matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Please change the name'],422);
    }
      
        try {
            DB::beginTransaction();
            
            $earning = Earning::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->earning()->save($earning);
            $earning->save();
            
            if (!$earning) {
                return response()->json(["error"=>"didnt work"],422);
            } 
            
            DB::commit();   
            return response()->json(["data"=>$earning]);
        }
            catch (\Exception $e) {
            DB::rollback();   
             
        return response()->json(["error"=>"didnt work"],422);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Earning  $earning
     * @return \Illuminate\Http\Response
     */
    public function show(Earning $earning)
    {
        // $Earning=Earning::all();
        // return response()->json(['earning' => $Earning]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Earning  $earning
     * @return \Illuminate\Http\Response
     */
    public function edit(Earning $earning)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Earning  $earning
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Earning $earning)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Earning  $earning
     * @return \Illuminate\Http\Response
     */
    public function destroy(Earning $earning)
    {
        //
    }
}
