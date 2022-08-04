<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Redis;
class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($id)
    {
        $cachedHoliday = Redis::get('holiday'.$id);


        if($cachedHoliday) {
            $cachedHoliday = json_decode($cachedHoliday, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedHoliday,
            ]);
        }else {
            $holiday = School::find($id)->holiday()->orderBy('created_at', 'desc')->get();
            Redis::set('holiday'.$id, $holiday);
            Redis::expire('holiday'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $holiday,
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
            'holiday_name', 'date'
         );
    
                              

        $validator = Validator::make($input, [
            'holiday_name' => 'required',
           
            'date' => 'required',
           
           
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = [
        'date' => $request->date,

       
       ];
       $found= School::find($id)->holiday()->where($matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'holiday exists'],422);

        }
       
        try {
            DB::beginTransaction();
            
            $holiday = Holiday::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->holiday()->save($holiday);
            $holiday->save();
            
            if (!$holiday) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            DB::commit();   
            return response()->json(["data"=>$holiday]);
        }
            catch (\Exception $e) {
            DB::rollback();   
             
        return response()->json(["error"=>"didnt work"],422);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        //
    }
}
