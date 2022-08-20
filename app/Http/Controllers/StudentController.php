<?php

namespace App\Http\Controllers;
use  App\Jobs\StudentEmailJob;
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
    public function classRoutine($id,$identity)
    {
        $student= Student::find($id);
        $school= School::where('identity_id','=',$identity)->first();
        $student_school=School::where('id','=',$student->school_id)->first();
        if($school == $student_school){
            $math=[

                'class' => $student->class,
                'section' => $student->section
            ];
           $class_routine =School::find($student_school->id)->classRoutine()->where($math)->get();
           return response()->json(['success'=>true, 'data' => $class_routine]);

        }
        return response()->json(['success'=>false, 'message' => 'listening from linux ubuntu'],422);



    }
    public function exam($id,$identity)
    {
        $student= Student::find($id);
        $school= School::where('identity_id','=',$identity)->first();
        $exam_school=School::where('id','=',$student->school_id)->first();
        if($school == $exam_school){
            $match=[

                'select_class' => $student->class,
                'select_section' => $student->section
            ];
           $class_exam =School::find($exam_school->id)->exam()->where($match)->get();
           return response()->json(['success'=>true, 'data' => $class_exam]);

        }
        return response()->json(['success'=>false, 'message' => 'listening from linux ubuntu'],422);



    }
    public function fee($id,$identity)
    {
        $student= Student::find($id);
        $school= School::where('identity_id','=',$identity)->first();
        $student_school=School::where('id','=',$student->school_id)->first();
        if($school == $student_school){
            $math=[

                'class' => $student->class,
                'section' => $student->section
            ];
           $class_fee =School::find($student_school->id)->fee()->where($math)->get();
           return response()->json(['success'=>true, 'data' => $class_fee]);

        }
        return response()->json(['success'=>false, 'message' => 'listening from linux ubuntu'],422);



    }
    public function personalData($id,$identity)
    {
        $student= Student::find($id);
        $school= School::where('identity_id','=',$identity)->first();
        $student_school=School::where('id','=',$student->school_id)->first();
        if($school == $student_school){
            $data=[
                'photo'=>$student->photo,
                'role'=>$student->role,
                'institution_name'=>$student_school->institution_name,
                'name'=>$student->first_name . $student->last_name ,
                
                'logo'=>$student_school->logo,
                ];
               return response()->json(['success'=>true, 'data' => $data]);

        }
        return response()->json(['success'=>false, 'message' => 'listening from linux ubuntu'],422);



    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response check
     */
    
    public function check()
    {
        return response()->json(['success'=>true, 'message' => 'listening from linux ubuntu'],422);

    }
    public function all($id)
    {
        // return response()->json(["SUDD"=>'fails']);

        $cachedstu = Redis::get('student'.$id);


        if($cachedstu) {
            $cachedstu = json_decode($cachedstu, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedstu
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
            $job=(new StudentEmailJob( $student->email,$student->password, $school->institution_name,$school->logo,))
            ->delay(Carbon::now()->addSeconds(5));
            dispatch( $job);
            return  response()->json(["success"=>"true"]);
        }
            catch (\Exception $e) {
            DB::rollback();   
             
        return response()->json(["error"=>"no process error!"],422);
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
       
        $input = $request->only('email', 'password','identity_id');
        $validator = Validator::make($input, [
            'identity_id' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);
        if($validator->fails()){
            return response()->json(["error"=>'email or password or identity_id fails'],422);
    
        }
        $matchThese = ['email' => $request->email];
        $student=Student::where($matchThese)->first();
        if(!$student){
            return response()->json(["error"=>'Email not found'],422);
    
        }
        $school=School::where('identity_id','=',  $request->identity_id)->first();
        if(!$school){
            return response()->json(["error"=>'Wrong institution code'],422);
    
        }
        $school_from_student=School::where('id','=',  $student->school_id)->first();
    
       if( $school == $school_from_student){
    
           
               // $date1 = Carbon::parse($found->payment_date);
               // $now = Carbon::now();
               // $diff = $date1->diffInDays($now);
               // if($diff >30){
               //     return response()->json(["success"=>$false,"message"=>"you need to pay minthly fee" ]);
               // }
               if (!Hash::check($request->password, $student->hashedPassword)) {
                   return response()->json(['success'=>false, 'message' => 'Login Fail, please check password'],422);
                }
                // $school=School::where('id','=',$found_admin->school_id)->first();
    
    
                $payload = JWTFactory::sub($student->id)
           // ->myCustomObject($account)
           ->make();
           $token = JWTAuth::encode($payload);
               return response()->json(['success'=>true, 
               'token' => '1'.$token ,
               "id"=>$student->id,
               'identity_id'=>$school->identity_id,
               'institution_name'=>$school->institution_name,
               'user_name'=>$student->first_name . $student->last_name,
               'role'=>$student->role,        
           ]);
    
           }
        
     
        
        return response()->json(['success'=>false, 'message' =>"Admin is not in the particular institution"],422);
    
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
    public function data( $id)
    {
        return response()->json(['success' => $id]);

    }
}
