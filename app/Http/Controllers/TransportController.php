<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Transport;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Redis;
class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($id)
    {
        $cachedTransport= Redis::get('transport'.$id);


        if($cachedTransport) {
            $cachedTransport = json_decode($cachedTransport, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedTransport,
            ]);
        }else {
            $transport = School::find($id)->transport()->orderBy('created_at', 'desc')->get();
            Redis::set('transport'.$id, $transport);
            Redis::expire('transport'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $transport,
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
            'route_name', 'vehicle_number', 'license_number'
        , 'phone_number', 'driver_name'
            
          );
    
                              

        $validator = Validator::make($input, [
            'route_name' => 'required',
            'vehicle_number' => 'required',
            'license_number' => 'required',
            'phone_number' => 'required',
            'driver_name' => 'required',
           
           
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = ['route_name' => $request->route_name,
         'vehicle_number' => $request->vehicle_number,
         'license_number' => $request->license_number,
         'phone_number' => $request->phone_number

        
        ];
        $found=Transport::where($matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'already  Exists']);

        }
        

        try {
            // begin transaction
            DB::beginTransaction();
         
            // write your dependent quires here
            $transport = Transport::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->transport()->save($transport);
            $transport->save();
            
            if (!$transport) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            return response()->json(["data"=>$transport]);
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
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function show(Transport $transport)
    {
        $Transport=Transport::all();
        return response()->json(['transport' => $Transport]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function edit(Transport $transport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transport $transport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transport $transport)
    {
        //
    }
}
