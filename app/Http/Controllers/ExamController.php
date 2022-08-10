<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Exam;
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
class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($id)
    {
        
        $cachedExam= Redis::get('exam'.$id);


        if($cachedExam) {
            $cachedExam = json_decode($cachedExam, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from redis',
                'data' => $cachedExam,
            ]);
        }else {
            $exam = School::find($id)->exam()->orderBy('created_at', 'desc')->get();
            Redis::set('exam'.$id, $exam);
            Redis::expire('exam'.$id,5);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $exam,
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
            'exam_name', 'select_date', 'subject_type', 'select_class'
            , 'select_section', 'select_time'
        );
    
                              

        $validator = Validator::make($input, [
            'exam_name' => 'required',
            'select_date' => 'required',
            'subject_type' => 'required',
            'select_class' => 'required',
            'select_section' => 'required',
            'select_time' => 'required'
         
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $matchThese = ['select_date' => $request->select_date, 'select_time' => $request->select_time,
                     'select_section' => $request->select_section, 'exam_name' => $request->exam_name,
                     'select_class' => $request->select_class ];
        $found=School::find($id)->exam()->where($matchThese )->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Exam Exists'],422);

        }
      

        try {
            // begin transaction
            DB::beginTransaction();
            
            // write your dependent quires here
            $exam = Exam::create($input); // eloquent creation of data
            $school=School::find($id);
            
            $school->exam()->save($exam);
            $exam->save();
            
            if (!$exam) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            return response()->json(["data"=>$exam]);
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
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        $all_exams = Exam::orderBy('created_at', 'desc')->get();
        return response()->json(["all_exams"=>$all_exams]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        //
    }
}
