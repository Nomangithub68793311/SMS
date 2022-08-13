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
use App\Models\School;
use Illuminate\Support\Facades\Redis;
use JWTAuth;
use JWTFactory;
class ClassRoutineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($id)
    {
        // return response()->json(["data"=>'yes']);

        $cachedClassRoutine = Redis::get('classRoutine'.$id);


        if($cachedClassRoutine) {
            $cachedClassRoutine = json_decode($cachedClassRoutine, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedClassRoutine,
            ]);
        }else {
            $classRoutine = School::find($id)->classRoutine()->orderBy('created_at', 'desc')->get();
            Redis::set('classRoutine'.$id, $classRoutine);
            Redis::expire('classRoutine'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $classRoutine,
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
            'teacher_name', 'class'
            , 'section', 'subject','date', 'time','email'
         );
    
                              

        $validator = Validator::make($input, [
            'teacher_name' => 'required',
           
            'class' => 'required',
            'section' => 'required',
            'subject' => 'required',
            'date' => 'required',
            'time' => 'required',
            'email' => 'required',
           
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = ['email' => $request->email,
        'class' => $request->class,
        'section' => $request->section,
        'date' => $request->date,
        'time' => $request->time

       
       ];
       $found= School::find($id)->classRoutine()->where($matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Class assaigned'],422);

        }
       
        try {
            DB::beginTransaction();
            
            $classRoutine = ClassRoutine::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->classRoutine()->save($classRoutine);
            $classRoutine->save();
            
            if (!$classRoutine) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            DB::commit();   
            return response()->json(["data"=>$classRoutine]);
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
