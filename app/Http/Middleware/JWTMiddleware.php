<?php

namespace App\Http\Middleware;
use Tymon\JWTAuth\Facades\JWTAuth;
use Closure;
use App\Models\School;
use App\Models\AdminUser;

use App\Models\Account;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\UnencryptedToken;
class JWTMiddleware 
{ 
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
    
    
    try {
        $id= $request->id;
    
        // return response()->json(['data' => "hello". $method]);

        if(!$id){
        return response()->json(['error' => 'id needed'],422);

        }

        $token = $request->bearerToken();
        if(!$token ){
            return response()->json(['message' => 'Authorization failed'], 422);

        }
        $tokenParts = explode(".", $token);  
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        if($id==$jwtPayload->sub){
            $user= School::find($jwtPayload->sub);
              if (!$user) {

                $admin= AdminUser::find($jwtPayload->sub);
              
                if(!$admin){
                    return response()->json(['message' => 'admin not found'], 422);

                }
                  if ($admin && $request->method()=='POST'){
                    $school=School::find($admin->school_id);
                   
                    $request->route()->setParameter('id',  $school->id);
                    return $next($request);
                   }
                   if ($admin && $request->method()=='GET'){
                    $school=School::find($admin->school_id);
                   
                    $request->route()->setParameter('id',  $school->id);
                    return $next($request);
                    

                   }   
                return $next($request);

            

                return response()->json(['message' => 'user not found'], 422);
              
                
                /////user finishes
             }

            return $next($request);
        }
        return response()->json(['message' => 'Something wrong'], 422);    

    }


///try finishes

                catch (Exception $e) {
                
                    return response()->json(['message' => 'invalid data'], 422);
                }
   
       
             
            
           
}  
    



}