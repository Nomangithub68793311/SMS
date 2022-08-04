<?php

namespace App\Http\Controllers;

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
     * @return \Illuminate\Http\Response check
     */
    public function create()
    {
        //
    }
    public function check()
    {
        return response()->json(['success'=>true, 'message' => 'listening from linux ubuntu'],422);

    }
    public function all($id)
    {
        $cachedstu = Redis::get('student'.$id);


        if($cachedstu) {
            $cachedstu = json_decode($cachedstu, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedstu,
            ]);
        }else {
            $student = School::find($id)->student;
            Redis::set('student'.$id, $student);
            Redis::expire('student'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $student,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
     public function store(Request $request,$id)
     {
         $input = $request->only('first_name', 'last_name','gender', 'date_of_birth', 'roll',
         'blood_group', 'religion', 'email','class', 'section', 'admission_id',
         'phone','address','bio','testimonial','certificate','signature','marksheet','photo'
      );
     
    
                              

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
            'date_of_birth' => 'required',

            'testimonial' => 'required',
            'certificate' => 'required',
            'signature' => 'required',
            'marksheet' => 'required',
            'photo' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = ['email' => $request->email];
      
        $found=Student::where($matchThese)->first();

        if($found){
            return response()->json(['success'=>false, 'message' => 'Email Exists'],422);

        }
        $found_with_admission_id=School::find($id)->student()->where('admission_id','=',$request->admission_id)->first();
        if($found_with_admission_id){
            return response()->json(['success'=>false, 'message' => 'Admission id should not be matched'],422);

        }
        $found_with_phone=Student::where('phone','=',$request->phone)->first();
        if($found_with_phone){
            return response()->json(['success'=>false, 'message' => 'phone number should not be matched'],422);

        }
        $matchThese = ['class' => $request->class,
        'section' => $request->section,
        'roll' => $request->roll];
        $found_with_roll=School::find($id)->student()->where($matchThese )->first();
        if($found_with_roll){
            return response()->json(['success'=>false, 'message' => 'Can not have same class and section with same roll number'],422);

        }

        $ranpass=Str::random(12);
        $input['password'] =$ranpass;
        $input['hashedPassword'] = Hash::make($ranpass); 
        try {
            DB::beginTransaction();
            $school=School::find($id);
            
            $student = Student::create($input); // eloquent creation of data
            $school->student()->save($student);
            $student->save();
            
            if (!$student) {
                return response()->json(["error"=>"didnt work"],422);
            } 
            // $response = Http::post('http://127.0.0.1:8000/v1/event', [
            //     "email"=>$student->email
                
            // ]);
            DB::commit();   
            return  response()->json(["email"=>$student->email,"pass"=>$student->password]);
        }
            catch (\Exception $e) {
            DB::rollback();   
             
        return response()->json(["error"=>"no process"],422);
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
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $validator = Validator::make($input, [
        
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);
        if($validator->fails()){
            return response()->json(["error"=>'email or password fail'],422);

        }
        $matchThese = ['email' => $request->email];
      
        $found=Student::where($matchThese)->first();
        if($found){
            // $date1 = Carbon::parse($found->payment_date);
            // $now = Carbon::now();
            // $diff = $date1->diffInDays($now);
            // if($diff >30){
            //     return response()->json(["success"=>$false,"message"=>"you need to pay minthly fee" ]);
            // }
            if (!Hash::check($request->password, $found->hashedPassword)) {
                return response()->json(['success'=>false, 'message' => 'Login Fail, please check password'],422);
             }
       
        // $customClaims = ['foo' => 'bar', 'baz' => 'bob'];


            $payload = JWTFactory::sub($found->id)
            // ->myCustomObject($customClaims)
            // ->prv(env('JWT_SECRET_PRV'))
            ->make();

        $token = JWTAuth::encode($payload);
            return response()->json(['success'=>true, 'token' => '1'.$token]);

        }
        return response()->json(['success'=>false, 'message' => 'Email not found!'],422);

    }

    public function show(Student $student)
    {
        // $days=Carbon::parse($dt)->daysInMonth;

        $all=Student::all();
        return response()->json(['student' => $all]);
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
