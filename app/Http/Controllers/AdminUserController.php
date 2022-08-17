<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\School;
use Illuminate\Support\Facades\Redis;
use  App\Jobs\AdminEmailJob;

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
    public function all($id)
    {
        $cachedAdminUser = Redis::get('cachedAdminUser'.$id);


        if($cachedAdminUser) {
            $cachedAdminUser = json_decode($cachedAdminUser, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedAdminUser,
            ]);
        }else {
            $adminUser = School::find($id)->adminUser;
            Redis::set('cachedAdminUser'.$id, $adminUser);
            Redis::expire('cachedAdminUser'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $adminUser,
            ]);
        }
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
      
        $user=AdminUser::where($matchThese)->first();
        if($user){
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
            return response()->json(['success'=>true, 'token' =>  $token ,'id'=>$user->school_id]);

        }
        return response()->json(['success'=>false, 'message' => 'Email not found!'],422);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $input = $request->only( 'first_name', 'last_name', 
        'gender','user_name','address',
        'joining_date', 'email','phone',  
     );
    
                   

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'user_name' => 'required',
            'joining_date' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchTheseSuperAdmin = ['id' => $id];
      
        $super_admin_found=School::where($matchTheseSuperAdmin)->first();
        if(!$super_admin_found){
            return response()->json(['success'=>false, 'message' => 'Only super admin can add users'],422);

        }
        $found_user_name=AdminUser::where('user_name','=', $request->user_name)->first();
        if($found_user_name){
            return response()->json(['success'=>false, 'message' => 'User name Exists'],422);

        }
        $matchThese = ['email' => $request->email];
      
        $found=School::find($id)->adminUser()->where($matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Email Exists'],422);

        }
      
        $found_with_phone=School::find($id)->adminUser()->where('phone','=',$request->phone)->first();
        if($found_with_phone){
            return response()->json(['success'=>false, 'message' => 'phone number should not be matched'],422);

        }
        $total_users = School::find($id)->adminUser()->count();
        if($total_users > 2){
            return response()->json(["message"=>"User subscription limit execeeds"],422);
 
        }
      

        $ranpass=Str::random(12);
        $input['password'] =$ranpass;
        $input['hashedPassword'] = Hash::make($ranpass); 
       
     

        try {
            DB::beginTransaction();
            
            $adminUser = AdminUser::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->adminUser()->save($adminUser);
            $adminUser->save();
            
            if (!$adminUser) {
                return response()->json(["error"=>"didnt work"],422);
            } 
            // $response = Http::post('http://127.0.0.1:8000/v1/event', [
            //     "email"=>$student->email
                
            // ]);
            DB::commit(); 
            $job=(new AdminEmailJob( $adminUser->email,$adminUser->password,  $school->institution_name,$school->logo))
            ->delay(Carbon::now()->addSeconds(5));
            dispatch( $job);  
            return  response()->json(["data"=>$adminUser->email]);
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
