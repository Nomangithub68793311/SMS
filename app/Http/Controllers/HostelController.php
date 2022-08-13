<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use App\Models\School;
use Illuminate\Support\Facades\Redis;
use JWTAuth;
use JWTFactory;
class HostelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($id)
    {

        $cachedHostel = Redis::get('hostel'.$id);


        if($cachedHostel) {
            $cachedHostel = json_decode($cachedHostel, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedHostel,
            ]);
        }else {
            $hostel = School::find($id)->hostel()->orderBy('created_at', 'desc')->get();
        
            Redis::set('hostel'.$id, $hostel);
            Redis::expire('hostel'.$id, 5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' =>$hostel,
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
            'hostel_name', 'room_number', 'room_type'
        , 'num_of_bed', 'cost_per_bed'
            
          );
    
                              

        $validator = Validator::make($input, [
            'hostel_name' => 'required',
            'room_number' => 'required',
            'room_type' => 'required',
            'num_of_bed' => 'required',
            'cost_per_bed' => 'required',
           
           
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails'],422);

        }
        // $matchThese = ['route_name' => $request->route_name,
        //  'vehicle_number' => $request->vehicle_number,
        //  'license_number' => $request->license_number,
        //  'phone_number' => $request->phone_number

        
        // ];
        // $found=Hostel::where($matchThese)->first();
        // if($found){
        //     return response()->json(['success'=>false, 'message' => 'already  Exists']);

        // }
        

        try {
            // begin transaction
            DB::beginTransaction();
            
            // write your dependent quires here
            $hostel = Hostel::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->hostel()->save($hostel);
            $hostel->save();
            
            if (!$hostel) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            return response()->json(["data"=>$hostel]);
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
     * @param  \App\Models\Hostel  $hostel
     * @return \Illuminate\Http\Response
     */
    public function show(Hostel $hostel)
    {
        $Hostel = Hostel::orderBy('created_at', 'desc')->get();
        return response()->json(["hostel"=>$Hostel]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hostel  $hostel
     * @return \Illuminate\Http\Response
     */
    public function edit(Hostel $hostel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hostel  $hostel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hostel $hostel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hostel  $hostel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hostel $hostel)
    {
        //
    }
}
