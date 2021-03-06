<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();

            return $next($request);
        } catch (Exception $exception) {
            if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status' => 'error', 'message' => 'Authorization token is Invalid'], Response::HTTP_UNAUTHORIZED);
            } else if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['status' => 'error', 'message' => 'Authorization token is Expired'], Response::HTTP_UNAUTHORIZED);
            } else if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json(['status' => 'error', 'message' => 'Authorization token is Blacklisted'], Response::HTTP_UNAUTHORIZED);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Authorization token not found'], Response::HTTP_UNAUTHORIZED);
            }
        }
    }
}
