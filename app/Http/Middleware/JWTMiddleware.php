<?php

namespace App\Http\Middleware;
use Tymon\JWTAuth\Facades\JWTAuth;
use Closure;
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
                $token = $request->bearerToken();
                $tokenParts = explode(".", $token);  
                $tokenHeader = base64_decode($tokenParts[0]);
                $tokenPayload = base64_decode($tokenParts[1]);
                $jwtHeader = json_decode($tokenHeader);
                $jwtPayload = json_decode($tokenPayload);
                if($id==$jwtPayload->sub){
                    $user= Account::find($jwtPayload->sub);
                    if (!$user) {
                        return response()->json(['message' => 'user not found'], 500);
                    }
                    // return response()->json(['message' => $id]);

                    return $next($request);
                }
                return response()->json(['message' => 'Something wrong'], 500);

             }
                catch (Exception $e) {
                
                    return response()->json(['message' => 'invalid data'], 500);
                }
   
       
             
            
           
}
    



}