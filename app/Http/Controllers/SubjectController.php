<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Subject;
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
class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($id)
    {
        $cachedSubject= Redis::get('subject'.$id);


        if($cachedSubject) {
            $cachedSubject = json_decode($cachedSubject, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedSubject,
            ]);
        }else {
            $subject = School::find($id)->subject()->orderBy('created_at', 'desc')->get();
            Redis::set('subject'.$id, $subject);
            Redis::expire('subject'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $subject,
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
            'subject_name', 'subject_type', 'select_class', 'select_code'
            
          );
    
                              

        $validator = Validator::make($input, [
            'subject_name' => 'required',
            'subject_type' => 'required',
            'select_class' => 'required',
            'select_code' => 'required'
          
           
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = [
            'subject_name' => $request->subject_name,
            'select_class' => $request->select_class,
            'select_code' => $request->select_code,
           ];
        $found=School::find($id)->subject()->where( $matchThese)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Not possible to asaign in the same class']);

        }
        

        try {
            // begin transaction
            DB::beginTransaction();
            
            // write your dependent quires here
            $subject = Subject::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->subject()->save($subject);
            $subject->save();
            
            if (!$subject) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            return response()->json(["data"=>$subject]);
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
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        // $Subject = Subject::orderBy('created_at', 'desc')->get();
        // return response()->json(["subject"=>$Subject]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        //
    }
}
