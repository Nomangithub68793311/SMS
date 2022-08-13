<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Admin;
use App\Models\Student;
use Carbon\Carbon;
use App\Models\Earning;
use Illuminate\Support\Facades\Redis;
use App\Models\School;
use App\Models\Notice;
use App\Models\Teacher;
use App\Models\Parentmodel;
use App\Models\AdminUser;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function showme($id)
    {
        return response()->json([
           
        'message' => 'Fetched from redis',
        'data' => "show me",
    ]);
      
    }

    public function all($id)
    {   

  

        $school=School::find($id);
////checking id is school or not

        if($school){
         $cachedInfo = Redis::hgetall('school'.$id);
///if cache exists
            if($cachedInfo) {
                  $newData=array();
      
               foreach ($cachedInfo as $key => $value) {
                  if($key == "institution_name" &&  "logo" &&  "role" && "user_name"){
                $newData[$key]=json_decode($value, TRUE);
                                                    }
                                $newData[$key]=json_decode($value, FALSE);
           
            }
                    return response()->json([
                
                        'message' =>'Fetched from redis',
                        'data' =>$newData
                    ]);
        }
///if cache does not exists
        try {
            // begin transaction
            DB::beginTransaction();
    

            // write your dependent quires here
            $total_students = School::find($id)->student; // eloquent creation of data
            

            $total_male=School::find($id)->student()->where('gender', 'male')->get();
            $total_female=School::find($id)->student()->where('gender', 'female')->get();
            $total_teachers = School::find($id)->teacher;
            $total_parents = School::find($id)->parentmodel;
            $total_expenses =School::find($id)->expense()->get()->sum("amount");
            $total_earnings =School::find($id)->earning()->get()->sum("amount");
        //    DB::table('notice')->orderBy('id')->chunk(3, function ($contacts) {
        //         foreach ($contacts as $contact) {
        //             echo $contacts;
        //         }
        //     });
       $notice= School::find($id)->notice;
            // $chunks = $notices->map(function($notice) {
            //     return $notice = $notice->values();
            //  });
            //  return $chunks;
            
            // if (!$total_students && !$total_male &&  !$total_female && !$total_teachers && !$total_parents) {
            //     return response()->json(["error"=>"not enough info"],422);
            // }
            if (!$total_students && !$total_earnings && !$notice && !$total_male &&  !$total_female && !$total_teachers && !$total_parents && !$total_expenses ) {
                return response()->json(["error"=>"not enough info"],422);
            }
            
    //        Happy ending :)
            DB::commit();  
        //    $all_keys= Redis::get('*'); 
            $data=[
                "total_students"=>$total_students->count(),
                "total_male"=>$total_male->count(),
                "total_female"=>$total_female->count(),
                "total_teachers"=>$total_teachers->count(),
                "total_parents"=>$total_parents->count(),
                "total_expenses"=>$total_expenses,
                "total_earnings"=>$total_earnings,
                "from"=>'super admin',
                "notice"=>$notice,
                "institution_name"=>$school->institution_name,
                "user_name"=>$school->user_name,
                "role"=>$school->role,
               "logo"=>$school->logo
            ];

            Redis::hmset('school'.$id, $data);
            Redis::expire('school'.$id,0);
            return response()->json([
                "data"=>$data,
                "message" => 'Fetched from database',

            
            ]);
        }
                    catch (\Exception $e) {
                    // May day,  rollback!!! rollback!!!
                       DB::rollback();      
                       return response()->json(["error"=>"not work again"],422);
                    }
         
        }
////checking id is admin_user or not

    $admin_user=AdminUser::find($id);

        if($admin_user){
          $school=School::find($admin_user->school_id);
        //  return response()->json(["data"=>$id]);

            if($school){
                $cachedInfo = Redis::hgetall('admin'.$id);
///if cache exists
              if($cachedInfo) {
      
                $newData=array();
      
                    foreach ($cachedInfo as $key => $value) {
                        if(is_string($key)){
                            $newData[$key]=$value;
                        }
                        $newData[$key]=json_decode($value, FALSE);
                        }
            
                    return response()->json([
                
                        'message' => 'Fetched from redis',
                        'data' => $newData,
                    ]);

                       }
        
    ///if cache does not exists

        try {
            // begin transaction
            DB::beginTransaction();
    

            // write your dependent quires here
            $total_students = School::find($school->id)->student; // eloquent creation of data
            // $school = School::find($id);

            $total_male=School::find($school->id)->student()->where('gender', 'male')->get();
            $total_female=School::find($school->id)->student()->where('gender', 'female')->get();
            $total_teachers = School::find($school->id)->teacher;
            $total_parents = School::find($school->id)->parentmodel;
            $total_expenses =School::find($school->id)->expense()->get()->sum("amount");
            $total_earnings =School::find($school->id)->earning()->get()->sum("amount");
        //    DB::table('notice')->orderBy('id')->chunk(3, function ($contacts) {
        //         foreach ($contacts as $contact) {
        //             echo $contacts;
        //         }
        //     });
            $notice= School::find($school->id)->notice()->orderBy('created_at', 'desc')->get();
            // $chunks = $notices->map(function($notice) {
            //     return $notice = $notice->values();
            //  });
            //  return $chunks;
            
            // if (!$total_students && !$total_male &&  !$total_female && !$total_teachers && !$total_parents) {
            //     return response()->json(["error"=>"not enough info"],422);
            // }
            if (!$total_students && !$total_earnings && !$notice && !$total_male &&  !$total_female && !$total_teachers && !$total_parents && !$total_expenses ) {
                return response()->json(["error"=>"not enough info"],422);
            }
            
    //        Happy ending :)
            DB::commit();  
        //    $all_keys= Redis::get('*'); 
            $data=[
                "total_students"=>$total_students->count(),
                "total_male"=>$total_male->count(),
                "total_female"=>$total_female->count(),
                "total_teachers"=>$total_teachers->count(),
                "total_parents"=>$total_parents->count(),
                "total_expenses"=>$total_expenses,
                "from"=>'admin',
                "total_earnings"=>$total_earnings,
                "notice"=> $notice,
                'institution_name'=>$school->institution_name,
                'user_name'=>$admin_user->user_name,
                'role'=>$admin_user->role,
                'logo'=>$school->logo

            ];

            Redis::hmset('admin'.$id, $data);
            Redis::expire('admin'.$id,0);
            return response()->json([
                "data"=>$data,
                "message" => 'Fetched from database',

            
            ]);
        }
            catch (\Exception $e) {
            // May day,  rollback!!! rollback!!!
            DB::rollback();   
             
        return response()->json(["error"=>"not work again"],422);
            }
         }
            
        }

        return response()->json(["error"=>"not work again"],422);
    
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
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    
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

        $matchThese = ['institution_email' => $request->email];
    //   super admin
        $found_super_admin=School::where($matchThese)->first();

        if($found_super_admin){
            // $date1 = Carbon::parse($found->payment_date);
            // $now = Carbon::now();
            // $diff = $date1->diffInDays($now);
            // if($diff >30){
            //     return response()->json(["success"=>$false,"message"=>"you need to pay minthly fee" ]);
            // }
            if (!Hash::check($request->password, $found_super_admin->hashedPassword)) {
                return response()->json(['success'=>false, 'message' => 'Login Fail, please check password'],422);
             }

             $payload = JWTFactory::sub($found_super_admin->id)
        // ->myCustomObject($account)
        ->make();
        $token = JWTAuth::encode($payload);
            return response()->json(['success'=>true, 
            'token' => '1'.$token ,
            "id"=>$found_super_admin->id,
            "from"=>"super admin",
            'institution_name'=>$found_super_admin->institution_name,
            'user_name'=>$found_super_admin->user_name,
            'role'=>$found_super_admin->role
        ]);

        }
      
        
        
        // admin login
        $matchThese = ['email' => $request->email];

        $found_admin=AdminUser::where($matchThese)->first();


        if($found_admin){
            // $date1 = Carbon::parse($found->payment_date);
            // $now = Carbon::now();
            // $diff = $date1->diffInDays($now);
            // if($diff >30){
            //     return response()->json(["success"=>$false,"message"=>"you need to pay minthly fee" ]);
            // }
            if (!Hash::check($request->password, $found_admin->hashedPassword)) {
                return response()->json(['success'=>false, 'message' => 'Login Fail, please check password'],422);
             }
             $school=School::where('id','=',$found_admin->school_id)->first();


             $payload = JWTFactory::sub($found_admin->id)
        // ->myCustomObject($account)
        ->make();
        $token = JWTAuth::encode($payload);
            return response()->json(['success'=>true, 
            'token' => '1'.$token ,
            "from"=>"admin",
            "id"=>$found_admin->id,
            'institution_name'=>$school->institution_name,
            'user_name'=>$found_admin->user_name,
            'role'=>$found_admin->role,        
        ]);

        }
        
        return response()->json(['success'=>false, 'message' =>"Email not found"],422);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */

    public function logout(Admin $admin)
    {
        //
    }
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
