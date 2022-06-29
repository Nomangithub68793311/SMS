<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\ClassRoutine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
class ClassRoutineController extends Controller
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
            'teacher_name', 'id_no', 'gender', 'class'
            , 'section', 'subject','date', 'time', 'phone', 'email'
         );
    
                              

        $validator = Validator::make($input, [
            'teacher_name' => 'required',
            'id_no' => 'required',
            'gender' => 'required',
            'class' => 'required',
            'section' => 'required',
            'subject' => 'required',
            'date' => 'required',
            'time' => 'required',
            'phone' => 'required',
            'email' => 'required',
           
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
    //     $matchThese = ['email' => $request->email,
    //     'roll' => $request->roll,
    //     'phone' => $request->phone,
    //     'admission_id' => $request->admission_id

       
    //    ];
    //    $found=Student::where($matchThese)->first();
    //     if($found){
    //         return response()->json(['success'=>false, 'message' => 'Email ,Roll,Phone,Admission_id Exists'],422);

    //     }
       
        try {
            DB::beginTransaction();
            
            $ClassRoutine = ClassRoutine::create($input); // eloquent creation of data

            
            if (!$ClassRoutine) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            DB::commit();   
            return response()->json(["routine"=>$ClassRoutine]);
        }
            catch (\Exception $e) {
            DB::rollback();   
             
        return response()->json(["error"=>"didnt work"],422);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassRoutine  $classRoutine
     * @return \Illuminate\Http\Response
     */
    public function show(ClassRoutine $classRoutine)
    {
        $all_class = ClassRoutine::orderBy('created_at', 'desc')->get();
        return response()->json(["all_class"=>$all_class]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassRoutine  $classRoutine
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassRoutine $classRoutine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassRoutine  $classRoutine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassRoutine $classRoutine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassRoutine  $classRoutine
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassRoutine $classRoutine)
    {
        //
    }
}
