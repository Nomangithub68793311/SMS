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
    public function all($id)
    {
        // return response()->json(["id"=>$id]);

        $cachedInfo = Redis::hgetall('yes'.$id);
        // return response()->json(["get"=> 'yes'.$id ]);

        if($cachedInfo) {
      
            return response()->json([
           
                'message' => 'Fetched from redis',
                'data' => $cachedInfo,
            ]);
        }else{

        try {
            // begin transaction
            DB::beginTransaction();
            
            // write your dependent quires here
            $total_students = Student::count(); // eloquent creation of data
            $total_male=Student::where('gender','male')->get()->count();
            $total_female=Student::where('gender','female')->get()->count();
            $total_teachers = Teacher::count();
            $total_parents = Parentmodel::count();
            $total_expenses =Expense::get()->sum("amount");
            $total_parents = Parentmodel::count();
            $total_earnings =Earning::get()->sum("amount");
        //    DB::table('notice')->orderBy('id')->chunk(3, function ($contacts) {
        //         foreach ($contacts as $contact) {
        //             echo $contacts;
        //         }
        //     });
       $notice= Notice::orderBy('created_at', 'desc')->get();
            // $chunks = $notices->map(function($notice) {
            //     return $notice = $notice->values();
            //  });
            //  return $chunks;
            
            if (!$total_students && !$total_earnings && !$notice && !$total_male &&  !$total_female && !$total_teachers && !$total_parents && !$total_expenses ) {
                return response()->json(["error"=>"not enough info"],422);
            }
            
    //        Happy ending :)
            DB::commit();  
        //    $all_keys= Redis::get('*'); 
            $data=[
                "total_students"=>$total_students,
                "total_male"=>$total_male,
                "total_female"=>$total_female,
                "total_teachers"=>$total_teachers,
                "total_parents"=>$total_parents,
                "total_expenses"=>$total_expenses,
                "total_earnings"=>$total_earnings,
                "notice"=> $notice,
            ];

            Redis::hmset('yes'.$id, $data);
            Redis::expire('yes'.$id,5);
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
        $input = $request->only('name', 'email', 'password');

        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 'Validation Error', 422);
        }
        $input['password'] = Hash::make($input['password']); // use bcrypt to hash the passwords
        $admin = Admin::create($input); // eloquent creation of data
        $payload = JWTFactory::sub($admin->id)
        // ->myCustomObject($account)
        ->make();
        $token = JWTAuth::encode($payload);
        return response()->json(["user"=>$admin ,'token' => '1'.$token]);


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
      
        $found=School::where($matchThese)->first();
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
        // ->myCustomObject($account)
        ->make();
        $token = JWTAuth::encode($payload);
            return response()->json(['success'=>true, 'token' => '1'. $token ,"id"=>$found->id]);

        }
        return response()->json(['success'=>false, 'message' => 'Email not found!'],422);

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
