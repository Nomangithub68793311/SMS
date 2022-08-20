<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Parentmodel;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
use  App\Jobs\ParentEmailJob;

class ParentmodelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function personalData($id,$identity)
    {
        $parent= Parentmodel::find($id);
        $school= School::where('identity_id','=',$identity)->first();
        $parent_school=School::where('id','=',$parent->school_id)->first();
        if($school == $parent_school){
            $data=[
                'role'=>$parent->role,
                'institution_name'=>$parent_school->institution_name,
                'name'=>$parent->first_name . $parent->last_name ,
                
                'logo'=>$parent_school->logo,
                ];
               return response()->json(['success'=>true, 'data' => $data]);

        }
        return response()->json(['success'=>false, 'message' => 'listening from linux ubuntu'],422);



    }
    public function all($id)
    {
        $cachedparent = Redis::get('parent'.$id);


        if($cachedparent) {
            $cachedparent = json_decode($cachedparent, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedparent,
            ]);
        }else {
            $parent = School::find($id)->parentmodel;
            Redis::set('parent'.$id, $parent);
            Redis::expire('parent'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $parent,
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
    public function store(Request $request ,$id)
    {

        $input = $request->only(  'first_name', 'last_name','gender', 
        'date_of_birth','occupation','student_email',
        'blood_group', 'religion', 'email',
        'phone','address','bio');
    
                              

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'blood_group' => 'required',
            'religion' => 'required',
            'occupation' => 'required',
            'student_email' => 'required',
            'phone' => 'required',
            'bio' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }


       $matchThese_stu = ['email' => $request->student_email];
       $found_with_student_email=Student::where($matchThese_stu)->first();
        if(!$found_with_student_email){
            return response()->json(['success'=>false, 'message' => 'Student email should exists'],422);

        }

        $matchThese = [
            'student_email' => $request->student_email,
            
           ];
           $found=Parentmodel::where($matchThese)->first();
           if($found){
               return response()->json(['success'=>false, 'message' => 'Student  Exists'],422);
    
           }
        $ranpass=Str::random(12);
        $input['password'] =$ranpass;
        $input['hashedPassword'] = Hash::make($ranpass); 
        // $Teacher = Teacher::create($input); // eloquent creation of data

        try {
            // begin transaction
            DB::beginTransaction();
           
            $parent = Parentmodel::create($input);
             // adding to school
             $school=School::find($id);
            $school->parentmodel()->save($parent);
            $parent->save();


            // adding parent to student
            $student=Student::where("email",'=',$parent->student_email)->first();
            $parent->student()->save($student);
            $student->save();
            $parent->save();
            if (!$parent) {
                return response()->json(["error"=>"didnt work"],422);
            }
          
            
            // Happy ending :)
            DB::commit();   
            $job=(new ParentEmailJob( $parent->email,$parent->password,  $school->institution_name,$school->logo,))
            ->delay(Carbon::now()->addSeconds(5));
            dispatch( $job);
            return response()->json(["data"=>"true "]);
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
        $parent=Parentmodel::where($matchThese)->first();
        if(!$parent){
            return response()->json(["error"=>'Email not found'],422);
    
        }
        $school=School::where('identity_id','=',  $request->identity_id)->first();
        if(!$school){
            return response()->json(["error"=>'Wrong institution code'],422);
    
        }
        $school_from_parent=School::where('id','=',  $parent->school_id)->first();
    
       if( $school == $school_from_parent){
    
           
               // $date1 = Carbon::parse($found->payment_date);
               // $now = Carbon::now();
               // $diff = $date1->diffInDays($now);
               // if($diff >30){
               //     return response()->json(["success"=>$false,"message"=>"you need to pay minthly fee" ]);
               // }
               if (!Hash::check($request->password, $parent->hashedPassword)) {
                   return response()->json(['success'=>false, 'message' => 'Login Fail, please check password'],422);
                }
                // $school=School::where('id','=',$found_admin->school_id)->first();
    
    
                $payload = JWTFactory::sub($parent->id)
           // ->myCustomObject($account)
           ->make();
           $token = JWTAuth::encode($payload);
               return response()->json(['success'=>true, 
               'token' => '1'.$token ,
               "id"=>$parent->id,
               'institution_name'=>$school->institution_name,
               'user_name'=>$parent->first_name . $parent->last_name,
               'role'=>$parent->role,        
           ]);
    
           }
        
     
        
        return response()->json(['success'=>false, 'message' =>"Admin is not in the particular institution"],422);

    }
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
    public function data( $id)
    {
        return response()->json(['success' => $id]);

    }
}
