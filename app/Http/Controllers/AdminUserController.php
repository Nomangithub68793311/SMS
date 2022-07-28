<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
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
    public function create($date1,$date2)
    {
        $date1 = Carbon::parse($date1);
        // $date2 = Carbon::parse($date2);
        
$now = Carbon::now();

$diff = $date1->diffInDays($now);
return response()->json(["diff"=>$diff ]);

    }
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $validator = Validator::make($input, [
        
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);
        $matchThese = ['email' => $request->email];
      
        $found=AdminUser::where($matchThese)->first();
        if($found){
            $date1 = Carbon::parse($found->payment_date);
            $now = Carbon::now();
            $diff = $date1->diffInDays($now);
            if($diff >30){
                return response()->json(["success"=>$false,"message"=>"you need to pay minthly fee" ]);
            }
            if (!Hash::check($request->password, $found->password)) {
                return response()->json(['success'=>false, 'message' => 'Login Fail, please check password']);
             }
             $payload = JWTFactory::sub($found->id)
        // ->myCustomObject($account)
        ->make();
        $token = JWTAuth::encode($payload);
            return response()->json(['success'=>true, 'token' =>  $token ]);

        }
        return response()->json(['success'=>false, 'message' => 'Email not found!'],422);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->only('first_name', 'last_name','gender', 'date_of_birth',
        'fathers_name', 'religion', 'email','mothers_name', 'joining_date',
        'phone','address','id_no'
     );
    
                   

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'fathers_name' => 'required',
            'religion' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required',
            'joining_date' => 'required',
            'mothers_name' => 'required',
            'id_no' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = ['email' => $request->email];
      
        $found=AdminUser::where($matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Email Exists'],422);

        }
        $found_with__id=AdminUser::where('id_no','=',$request->id_no)->first();
        if($found_with__id){
            return response()->json(['success'=>false, 'message' => 'id should not be matched'],422);

        }
        $found_with_phone=AdminUser::where('phone','=',$request->phone)->first();
        if($found_with_phone){
            return response()->json(['success'=>false, 'message' => 'phone number should not be matched'],422);

        }
        $total_users = AdminUser::count();
        if($total_users > 4){
            return response()->json(["message"=>"User subscription limit execeeds"],422);
 
        }
      

        $ranpass=Str::random(12);
        $input['password'] =$ranpass;
        $input['hashedPassword'] = Hash::make($ranpass); 
       
     

        try {
            DB::beginTransaction();
            
            $admin_user = AdminUser::create($input); // eloquent creation of data

            
            if (!$admin_user) {
                return response()->json(["error"=>"didnt work"],422);
            } 
            // $response = Http::post('http://127.0.0.1:8000/v1/event', [
            //     "email"=>$student->email
                
            // ]);
            DB::commit();   
            return  response()->json(["email"=>$admin_user->email]);
        }
            catch (\Exception $e) {
            DB::rollback();   
             
        return response()->json(["error"=>"no process"],422);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminUser  $adminUser
     * @return \Illuminate\Http\Response
     */
    public function show(AdminUser $adminUser)
    {
        $all=AdminUser::all();
        return response()->json(['user' => $all]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdminUser  $adminUser
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminUser $adminUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminUser  $adminUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminUser $adminUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminUser  $adminUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminUser $adminUser)
    {
        //
    }
}
