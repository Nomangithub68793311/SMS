<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\ClassRoutine;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $input = $request->only(
            'class', 'section', 'fee_name', 'fee_amount'
            , 'fee_type', 'starts_from','finishes_at', 
         );
    
                              

        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
            'fee_amount' => 'required',
            'fee_type' => 'required',
            'starts_from' => 'required',
            'finishes_at' => 'required',
            'fee_name' => 'required'
          
           
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = ['fee_name' => $request->fee_name ];
       $found=Fee::where($matchThese)->first();
        if($found){
            return response()->json(['Already exists with same name'],422);

        }
       
        try {
            DB::beginTransaction();
            
            $fee = Fee::create($input); // eloquent creation of data

            
            if (!$fee) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            DB::commit();   
            return response()->json(["fee"=>$fee]);
        }
            catch (\Exception $e) {
            DB::rollback();   
             
        return response()->json(["error"=>"didnt work"],422);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function show(Fee $fee)


    {
        $all_fees = Fee::orderBy('created_at', 'desc')->get();
        return response()->json(["all_fees"=>$all_fees]);


       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function edit(Fee $fee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fee $fee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fee $fee)
    {
        //
    }
}
