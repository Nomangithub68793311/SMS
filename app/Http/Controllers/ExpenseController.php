<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
class ExpenseController extends Controller
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
        $input = $request->only('name', 'id_no', 'expense_type', 'amount',"date",'phone'
        , 'email', 'status',
          );
    
                              

        $validator = Validator::make($input, [
            'name' => 'required',
            'id_no' => 'required',
            'expense_type' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'status' => 'required'
           
        ]);

        if($validator->fails()){
            return response()->json(["error"=>'fails']);

        }
        $found=Expense::where('id_no','=',$request->id_no)->first();
        if($found){
            return response()->json(['success'=>false, 'message' => 'Book Exists']);

        }
        

        try {
            // begin transaction
            DB::beginTransaction();
            
            // write your dependent quires here
            $expense = Expense::create($input); // eloquent creation of data

            
            if (!$expense) {
                return response()->json(["error"=>"didnt work"],422);
            }
            
            // Happy ending :)
            DB::commit();   
            return response()->json(["expense"=>$expense]);
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
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        $all_expenses = Expense::orderBy('created_at', 'desc')->get();
        return response()->json(["all_expenses"=>$all_expenses]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
