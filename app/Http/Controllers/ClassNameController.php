<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ClassName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
class ClassNameController extends Controller
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
            'teacher_name', 'gender', 'class', 'id_no'
            ,'phone', 'subject', 'section' ,'email', 'date', 
            'time',
            
          );
    
                              

        $validator = Validator::make($input, [
            'teacher_name' => 'required',
            'gender' => 'required',
            'class' => 'required',
            'id_no' => 'required',
            'phone' => 'required',
            'subject' => 'required',
            'section' => 'required',
            'email' => 'required',
            'date' => 'required',
            'time' => 'required'
           
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $found=ClassName::where('id_no','=',$request->id_no)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Id Exists'],422);

        }
        

        try {
            // begin transaction
            DB::beginTransaction();
            
            // write your dependent quires here
            $class = ClassName::create($input); // eloquent creation of data

            
            if (!$class) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            return response()->json(["class"=>$class]);
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
     * @param  \App\Models\ClassName  $className
     * @return \Illuminate\Http\Response
     */
    public function show(ClassName $className)
    {
        $class = ClassName::orderBy('created_at', 'desc')->get();
        return response()->json(["class"=>$class]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassName  $className
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassName $className)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassName  $className
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassName $className)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassName  $className
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassName $className)
    {
        //
    }
}
