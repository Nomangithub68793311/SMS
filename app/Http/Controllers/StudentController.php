<?php

namespace App\Http\Controllers;

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
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $all=Student::all();
        return response()->json(['data' => $all]);

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
        $input = $request->only('first_name', 'last_name','gender', 'date_of_birth', 'roll',
        'blood_group', 'religion', 'email','class', 'section', 'admission_id',
        'phone','address','bio' );
    
                              

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'roll' => 'required',
            'blood_group' => 'required',
            'religion' => 'required',
            'class' => 'required',
            'section' => 'required',
            'admission_id' => 'required',
            'phone' => 'required',
            'bio' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = ['email' => $request->email,
        'roll' => $request->roll,
        'phone' => $request->phone,
        'admission_id' => $request->admission_id

       
       ];
       $found=Student::where($matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Email ,Roll,Phone,Admission_id Exists'],422);

        }
        $ranpass=Str::random(12);
        $input['password'] =$ranpass;
        $input['hashedPassword'] = Hash::make($ranpass); 
        try {
            DB::beginTransaction();
            
            $student = Student::create($input); // eloquent creation of data

            
            if (!$student) {
                return response()->json(["error"=>"didnt work"],422);
            } 
            
            DB::commit();   
            return response()->json(["email"=>$student->email,"pass"=>$student->password]);
        }
            catch (\Exception $e) {
            DB::rollback();   
             
        return response()->json(["error"=>"didnt work"],422);
    }
        // $payload = JWTFactory::sub($student->id)
        // ->myCustomObject($account)
        // ->make();
        // $token = JWTAuth::encode($payload);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        // $days=Carbon::parse($dt)->daysInMonth;

        $all=Student::all();
        return response()->json(['data' => $all]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
