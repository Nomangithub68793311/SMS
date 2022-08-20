<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Redis;
use  App\Jobs\TeacherEmailJob;
use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function personalData($id,$identity)
    {
        $teacher= Teacher::find($id);
        $school= School::where('identity_id','=',$identity)->first();
        $teacher_school=School::where('id','=',$teacher->school_id)->first();
        if($school == $teacher_school){
            $data=[
                'role'=>$teacher->role,
                'institution_name'=>$teacher_school->institution_name,
                'name'=>$teacher->first_name . $teacher->last_name ,
                
                'logo'=>$teacher_school->logo,
                ];
               return response()->json(['success'=>true, 'data' => $data]);

        }
        return response()->json(['success'=>false, 'message' => 'listening from linux ubuntu'],422);



    }
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
    public function all($id)
    {
        $cachedsteacher = Redis::get('teacher'.$id);


        if($cachedsteacher) {
            $cachedsteacher = json_decode($cachedsteacher, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedsteacher,
            ]);
        }else {
            $teacher = School::find($id)->teacher;
            Redis::set('teacher'.$id, $teacher);
            Redis::expire('teacher'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $teacher,
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
        $input = $request->only('first_name', 'last_name','gender', 'date_of_birth', 'id_no',
        'blood_group', 'religion', 'email','class', 'section', 'admission_id',
        'phone','address','bio' );
    
                              

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'id_no' => 'required',
            'blood_group' => 'required',
            'religion' => 'required',
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

        $matchThese = ['email' => $request->email ];
       $found=School::find($id)->teacher()->where($matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Email Exists'],422);

        }
        $matchThese = ['id_no' => $request->id_no];
       $found_wth_id=School::find($id)->teacher()->where($matchThese)->first();
        if($found_wth_id){
            return response()->json(['success'=>false, 'message' => 'Id_no Exists'],422);

        }
        $matchThese = [
        'phone' => $request->phone
       ];
       $found_with_phone=School::find($id)->teacher()->where($matchThese)->first();
        if($found_with_phone){
            return response()->json(['success'=>false, 'message' => 'Phone Exists'],422);

        }
       
        $ranpass=Str::random(12);
        $input['password'] =$ranpass;
        $input['hashedPassword'] = Hash::make($ranpass); 
        // $Teacher = Teacher::create($input); // eloquent creation of data

        try {
            // begin transaction
            DB::beginTransaction();
            $school=School::find($id);
            // write your dependent quires here
            $teacher = Teacher::create($input); // eloquent creation of data

            $school->teacher()->save($teacher);
            $teacher->save();
            if (!$teacher) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            $job=(new TeacherEmailJob( $teacher->email,$teacher->password,  $school->institution_name,$school->logo))
            ->delay(Carbon::now()->addSeconds(5));
            dispatch( $job);
            return response()->json(["data"=>'true']);
        }
            catch (\Exception $e) {
            // May day,  rollback!!! rollback!!!
            DB::rollback();   
             
        return response()->json(["error"=>"didnt work"],422);
    }
        // $student = Teacher::create($input); // eloquent creation of data
        // $payload = JWTFactory::sub($student->id)
        // ->myCustomObject($account)
        // ->make();
        // $token = JWTAuth::encode($payload);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
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
        $teacher=Teacher::where($matchThese)->first();
        if(!$teacher){
            return response()->json(["error"=>'Email not found'],422);
    
        }
        $school=School::where('identity_id','=',  $request->identity_id)->first();
        if(!$school){
            return response()->json(["error"=>'Wrong institution code'],422);
    
        }
        $school_from_teacher=School::where('id','=',  $teacher->school_id)->first();
    
       if( $school == $school_from_teacher){
    
           
               // $date1 = Carbon::parse($found->payment_date);
               // $now = Carbon::now();
               // $diff = $date1->diffInDays($now);
               // if($diff >30){
               //     return response()->json(["success"=>$false,"message"=>"you need to pay minthly fee" ]);
               // }
               if (!Hash::check($request->password, $teacher->hashedPassword)) {
                   return response()->json(['success'=>false, 'message' => 'Login Fail, please check password'],422);
                }
                // $school=School::where('id','=',$found_admin->school_id)->first();
    
    
                $payload = JWTFactory::sub($teacher->id)
           // ->myCustomObject($account)
           ->make();
           $token = JWTAuth::encode($payload);
               return response()->json(['success'=>true, 
               'token' => '1'.$token ,
               "id"=>$teacher->id,
               'institution_name'=>$school->institution_name,
               'user_name'=>$teacher->first_name . $teacher->last_name,
               'role'=>$teacher->role,        
           ]);
    
           }
        
     
        
        return response()->json(['success'=>false, 'message' =>"Admin is not in the particular institution"],422);
    } 
    public function show(Teacher $teacher)
    {
         $all=Teacher::all();
        return response()->json(['data' => $all]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function data( $id)
    {
        return response()->json(['success' => $id]);

    }
}
