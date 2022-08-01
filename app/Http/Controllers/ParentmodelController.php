<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Parentmodel;
use App\Models\Student;
use App\Models\AdminSignup;
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
        'class' => $request->class,
        'section' => $request->section,
       ];
       $found=Parentmodel::where($matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Student email Exists'],422);

        }
        $ranpass=Str::random(12);
        $input['password'] =$ranpass;
        $input['hashedPassword'] = Hash::make($ranpass); 
        // $Teacher = Teacher::create($input); // eloquent creation of data

        try {
            // begin transaction
            DB::beginTransaction();
            $school=AdminSignup::find($id);
            // write your dependent quires here
            $parent = Parentmodel::create($input); // eloquent creation of data
            $school->parentmodel()->save($parent);
            $parent->save();
            
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
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $validator = Validator::make($input, [
        
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);
        if($validator->fails()){
            return response()->json(["error"=>"email or password fail"],422);

        }
        $matchThese = ['email' => $request->email];
      
        $found=Parentmodel::where($matchThese)->first();
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
             $payload = JWTFactory::sub($found->id)
             // ->myCustomObject($customClaims)
             // ->prv(env('JWT_SECRET_PRV'))
             ->make();
 
         $token = JWTAuth::encode($payload);
             return response()->json(['success'=>true, 'token' => '1'.$token]);

        }
        return response()->json(['success'=>false, 'message' => 'Email not found!'],422);

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
    public function destroy(Parentmodel $parentmodel)
    {
        //
    }
}
