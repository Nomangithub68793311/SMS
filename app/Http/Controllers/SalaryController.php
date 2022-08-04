<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Parentmodel;
use App\Models\Student;
use App\Models\School;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($id)
    {
        $cachedSalary = Redis::get('salary'.$id);


        if($cachedSalary) {
            $cachedSalary = json_decode($cachedSalary, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedSalary,
            ]);
        }else {
            $salary = School::find($id)->salary()->orderBy('created_at', 'desc')->get();
            Redis::set('salary'.$id, $salary);
            Redis::expire('salary'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $salary,
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
        $input = $request->only(
            'staff_id', 'name', 'gender', 'month'
        , 'amount', 'email',
        );
    
                              

        $validator = Validator::make($input, [
            'staff_id' => 'required',
            'name' => 'required',
            'gender' => 'required',
            'month' => 'required',
            'amount' => 'required',
            'email' => 'required',
           
         
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'invalid data']);

        }
        $matchThese = ['staff_id' => $request->staff_id, 'email' => $request->email];
        $found=School::find($id)->salary()->where($matchThese )->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Added Already'],422);

        }
      

        try {
            // begin transaction
            DB::beginTransaction();
            
            // write your dependent quires here
            $salary = Salary::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->salary()->save($salary);
            $salary->save();
            
            if (!$salary) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            return response()->json(["data"=>$salary]);
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
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show(Salary $salary)
    {
        // $Salary = Salary::orderBy('created_at', 'desc')->get();
        // return response()->json(["salary"=>$Salary]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit(Salary $salary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $salary)
    {
        //
    }
}
