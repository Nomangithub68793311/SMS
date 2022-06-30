<?php

namespace App\Http\Controllers;

use App\Models\Earning;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
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
            'name', 'amount', 'type', 'date'
        );
    
                              

        $validator = Validator::make($input, [
            'name' => 'required',
            'amount' => 'required',
            'type' => 'required',
            'date' => 'required'
            
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }

    //     $matchThese = ['email' => $request->email,
    //     'roll' => $request->roll,
    //     'phone' => $request->phone,
    //     'admission_id' => $request->admission_id
    //    ];
    //    $found=Earning::where($matchThese)->first();
    //     if($found){
    //         return response()->json(['success'=>false, 'message' => 'Email ,Roll,Phone,Admission_id Exists'],422);
    //     }
      
        try {
            DB::beginTransaction();
            
            $Earning = Earning::create($input); // eloquent creation of data

            
            if (!$Earning) {
                return response()->json(["error"=>"didnt work"],422);
            } 
            
            DB::commit();   
            return response()->json(["Earning"=>$Earning]);
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
        $Earning=Earning::all();
        return response()->json(['earning' => $Earning]);
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
