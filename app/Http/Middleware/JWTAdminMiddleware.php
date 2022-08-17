<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AdminUser;

class JWTAdminMiddleware
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
                $user= AdminUser::find($jwtPayload->sub);
                if (!$user) {
                    
                    return response()->json(['message' => 'user not found'], 422);
                }
                // return response()->json(['message' => $id]);

                return $next($request);
            }
            return response()->json(['message' => 'Something wrong'], 422); 

         }
            catch (Exception $e) {
            
                return response()->json(['message' => 'invalid data'], 422);
            }
    }
}
