<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Parentmodel;
use App\Models\Student;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
class ParentmodelController extends Controller
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
        $input = $request->only(  'first_name', 'last_name','gender', 'date_of_birth', 'id_no','occupation','student_email',
        'blood_group', 'religion', 'email','class', 'section',
        'phone','address','bio');
    
                              

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'id_no' => 'required',
            'blood_group' => 'required',
            'religion' => 'required',
            'occupation' => 'required',
            'student_email' => 'required',
            'class' => 'required',
            'section' => 'required',
            'phone' => 'required',
            'bio' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = [
        'id_no' => $request->id_no,
       
        'student_email' => $request->student_email,
       ];
       $found=Parentmodel::where($matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Email ,Phone,Id_no Exists'],422);

        }
        $ranpass=Str::random(12);
        $input['password'] =$ranpass;
        $input['hashedPassword'] = Hash::make($ranpass); 
        // $Teacher = Teacher::create($input); // eloquent creation of data

        try {
            // begin transaction
            DB::beginTransaction();
            
            // write your dependent quires here
            $parent = Parentmodel::create($input); // eloquent creation of data

            
            if (!$parent) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            return response()->json(["parent"=>$parent]);
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
     * @param  \App\Models\Parentmodel  $parentmodel
     * @return \Illuminate\Http\Response
     */
    public function show(Parentmodel $parentmodel)
    {
        $all=Parentmodel::all();
        return response()->json(['data' => $all]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Parentmodel  $parentmodel
     * @return \Illuminate\Http\Response
     */
    public function edit(Parentmodel $parentmodel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parentmodel  $parentmodel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parentmodel $parentmodel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Parentmodel  $parentmodel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parentmodel $parentmodel)
    {
        //
    }
}
