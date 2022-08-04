<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Redis;

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
    public function index()
    {
        //
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
            return response()->json(['success'=>false, 'message' => 'Email Exists']);

        }
        $matchThese = ['id_no' => $request->id_no];
       $found_wth_id=School::find($id)->teacher()->where($matchThese)->first();
        if($found_wth_id){
            return response()->json(['success'=>false, 'message' => 'Id_no Exists']);

        }
        $matchThese = [
        'phone' => $request->phone
       ];
       $found_with_phone=School::find($id)->teacher()->where($matchThese)->first();
        if($found_with_phone){
            return response()->json(['success'=>false, 'message' => 'Phone Exists']);

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
            return response()->json(["Teacher"=>$teacher->email,"pass"=>$teacher->password]);
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
        $input = $request->only('email', 'password');
        $validator = Validator::make($input, [
        
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);
        if($validator->fails()){
            return response()->json(["error"=>'email or password fail'],422);

        }
        $matchThese = ['email' => $request->email];
      
        $found=Teacher::where($matchThese)->first();
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
    public function destroy(Teacher $teacher)
    {
        //
    }
}
