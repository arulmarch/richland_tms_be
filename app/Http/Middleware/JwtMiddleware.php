<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\Models\MasterDriver;
use App\Models\MasterUser;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('Authorization');

        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                "code" => "05",
                "message" => "Token tidak di sediakan"
            ], 401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                "code" => "05",
                "message" => "Token telah kadaluwarsa"
            ], 401);
        } catch(Exception $e) {
            return response()->json([
                "code" => "05",
                "message" =>  "Terjadi kesalahan pada token",
            ], 401);
        }
        $user_driver = MasterDriver::where('id', $credentials->sub)->where('token', $credentials->random)->first();
        $user = MasterUser::where('user_id', $credentials->sub)->where('token', $credentials->random)->first();
        if ($user_driver) {
            $request->auth = $user_driver;
            return $next($request);
        } else if ($user) {
            $request->auth = $user;
            return $next($request);
        } else {
            return response()->json([
                "code" => "05",
                "message" =>  "Token yang diberikan dinonaktifkan",
            ], 401);
        }
    }
}
