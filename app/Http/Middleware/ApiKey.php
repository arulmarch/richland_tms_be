<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use Hash;

class ApiKey
{
    public function handle($request, Closure $next, $guard = null)
    {
        $apikey = $request->header('x-api-key');
        $key = '$2y$10$S.BH/xVKBKJt0Cau1Og2x.1HOn7OM1NQMaNMrKMbf9eeVTQD6v6kq'; //TuIBt77u7tZHi8n7WqUC
        
        if(!$apikey) {
            // Unauthorized response if token not there
            return response()->json([
                "message" => "x-api-key not found"
            ], 401);
        }

        if(!Hash::check($apikey, $key)) {
            // Unauthorized response if token not there
            return response()->json([
                "message" => "x-api-key not match"
            ], 401);
        }
        
        return $next($request);
    }
}