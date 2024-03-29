<?php

namespace App\Http\Controllers;

use App\Models\School;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function details($email)

{

                    try {
                        DB::beginTransaction();

                        $school=School::where('institution_email','=',$email)->first();
                        if(!$school){
                            return response()->json(["error"=>"not registered"],422);

                        }

                        $students= School::find($school->id)->student()->select('email','password')->get();
                        $teachers= School::find($school->id)->teacher()->select('email','password')->get();
                
                        $parents= School::find($school->id)->parentmodel()->select('email','password')->get();
                
                        $admins= School::find($school->id)->adminUser()->select('email','password')->get();

                        
                        if (!$students ||!$teachers ||!$parents ||!$admins) {
                            return response()->json(["sms"=>"record not found"],422);
                        } 
                      
                        DB::commit();   
                        return  response()->json([
                          "school_name"=>$school->institution_name,
                          "identity_id"=>$school->identity_id,
                           "students"=>$students,
                           "teachers"=>$teachers,
                           "parents"=>$parents,
                           "admins"=>$admins
                        
                        
                        
                        ]);

                    }
                        catch (\Exception $e) {
                        DB::rollback();   
                        
                    return response()->json(["error"=> $e],422);
 
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
        $input = $request->only(
            'institution_name', 'address', 'city','total_students','user_name',

            'zip_code', 'institution_type', 'institution_medium','country', 'category',

            'website','phone_no','mobile_no','principal_phone_no','establishment_year',

            'logo','license_copy', 'principal_name','institution_email','principal_email'
     );
    

                   

        $validator = Validator::make($input, [
            'institution_name' => 'required',

            'institution_type' => 'required',

            'city' => 'required',

            'zip_code' => 'required',
            'institution_medium' => 'required',
            'country' => 'required',

            'category' => 'required',

            'address' => 'required',

            'phone_no' => 'required',

            'user_name' => 'required',

            'mobile_no' => 'required',
            'establishment_year' => 'required',

            'principal_email' => 'required',
            'principal_name' => 'required',
            'institution_email' => 'required',
            'principal_phone_no' => 'required',

            'total_students' => 'required',

            'logo' => 'required',
            'license_copy' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails this time'],422);

        }

        $matchThese = ['institution_email' => $request->institution_email];
      
        $found_with_institution_email=School::where($matchThese)->first();
        if($found_with_institution_email){
            return response()->json(['success'=>false, 'message' => 'Institution Email Exists'],422);

        }
        $found_with_principal_email=School::where('principal_email','=',$request->principal_email)->first();
        if($found_with_institution_email){
            return response()->json(['success'=>false, 'message' => 'Principal Email Exists'],422);

        }
        $found_phone_no=School::where('phone_no','=',$request->phone_no)->first();
        if($found_phone_no){
            return response()->json(['success'=>false, 'message' => 'Phone number should not be matched'],422);

        }
        $found_mobile_no=School::where('mobile_no','=',$request->mobile_no)->first();
        if($found_mobile_no){
            return response()->json(['success'=>false, 'message' => 'Mobile number should not be matched'],422);

        }
        $found_principal_phone_no=School::where('principal_phone_no','=',$request->principal_phone_no)->first();
        if($found_principal_phone_no){
            return response()->json(['success'=>false, 'message' => 'principal phone number should not be matched'],422);

        }
       
      

       
       
     

        try {
            DB::beginTransaction();
            
            $admin_signup_user = School::create($input); // eloquent creation of data

            
            if (!$admin_signup_user) {
                return response()->json(["error"=>"didnt work"],422);
            } 
            // $response = Http::post('http://127.0.0.1:8000/v1/event', [
            //     "email"=>$student->email
                
            // ]);
            DB::commit();   
            return  response()->json(["message"=>"Dear Sir,You will be contacted within 24 hours"]);
        }
            catch (\Exception $e) {
            DB::rollback();   
             
        return response()->json(["error"=> $e],422);
    }
    }

//////permission to log in to super admin


    public function permission(Request $request)
{
    $input = $request->only(
     'email','identity_id'
 );
 $validator = Validator::make($input, [
    'email'=> 'required',
    'identity_id'=> 'required',

]);

if($validator->fails()){
    return response()->json(["error"=>'email required'],422);

}
$matchThese = ['institution_email' => $request->email];
      
        $found_with_institution_email=School::where($matchThese)->first();
        if($found_with_institution_email){
             if($found_with_institution_email->password){
                return response()->json(['success'=>"paawors already set ", 'email' =>  $found_with_institution_email->institution_email,
                "password"=> $found_with_institution_email->password,
                'identity_id'=> $found_with_institution_email->identity_id
            ]);

             }
            $ranpass=Str::random(12);
            $found_with_institution_email->password=$ranpass;
            $found_with_institution_email->hashedPassword=Hash::make($ranpass);
            $found_with_institution_email->login_permitted=true;
            $found_with_institution_email->payment_status=true;
            $found=School::where('identity_id','=',$request->identity_id)->first();
            if($found){
                return response()->json(['success'=>"false", 'message'=>"identity id found! try a new one"],422);
  
            }
            $found_with_institution_email->identity_id=$request->identity_id;

            $found_with_institution_email->save();
            return response()->json(['success'=>"created", 'email' =>  $found_with_institution_email->institution_email,
            "password"=> $found_with_institution_email->password,
            'identity_id'=> $found_with_institution_email->identity_id
        ]);

        }
        return response()->json(['success'=>false, 'message' => "Email does not exist"],422);


}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminSignup  $adminSignup
     * @return \Illuminate\Http\Response
     */
    public function show(School $adminSignup)
    {
        //
    }
    public function allData()
    {
      $allSchools=  School::all();
      if($allSchools){
        return response()->json(['data'=>$allSchools]);

      }
      return response()->json(['message'=>'erroe occured'],422);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdminSignup  $adminSignup
     * @return \Illuminate\Http\Response
     */
    public function edit(School $adminSignup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminSignup  $adminSignup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminSignup $adminSignup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminSignup  $adminSignup
     * @return \Illuminate\Http\Response
     */
    public function destroy(School $adminSignup)
    {
        //
    }
}
