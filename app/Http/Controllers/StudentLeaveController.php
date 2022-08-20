<?php

namespace App\Http\Controllers;

use App\Models\StudentLeave;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use App\Models\School;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use Illuminate\Support\Facades\Http;
use JWTFactory;

class StudentLeaveController extends Controller
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
    public function store(Request $request,$id,$identity)
    {
        $input = $request->only(
            'leave_name', 'name', 'class', 'section'
        , 'email',  'reason'
        , 'total_days', 'start_date', 'finish_date',
        );
    
                              

        $validator = Validator::make($input, [
            'leave_name' => 'required',
            'class' => 'required',
            'section' => 'required',
            'name' => 'required', 
            'amount' => 'required',
            'email' => 'required',
            'total_days' => 'required',
            'start_date' => 'required', 
            'finish_date' => 'required',
         
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'invalid data']);

        }
        $student= Student::find($id);
        $school= School::where('identity_id','=',$identity)->first();
        $stuent_school=School::where('id','=',$student->school_id)->first();
        $matchThese = ['staff_id' => $request->staff_id, 'email' => $request->email];
        $found=School::find($id)->salary()->where($matchThese )->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Added Already'],422);

        }
      

        try {
            // begin transaction
            DB::beginTransaction();
            
            // write your dependent quires here
            $salary = Salary::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->salary()->save($salary);
            $salary->save();
            
            if (!$salary) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            return response()->json(["data"=>$salary]);
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
     * @param  \App\Models\StudentLeave  $studentLeave
     * @return \Illuminate\Http\Response
     */
    public function show(StudentLeave $studentLeave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentLeave  $studentLeave
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentLeave $studentLeave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentLeave  $studentLeave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentLeave $studentLeave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentLeave  $studentLeave
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentLeave $studentLeave)
    {
        //
    }
}
