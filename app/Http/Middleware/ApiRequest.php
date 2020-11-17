<?php

namespace App\Http\Middleware;

use Closure;

class ApiRequest
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
        if($request->header('X-Requested-With')){
            return $next($request);
        }
        return response()->json([
            'status'=>false,
            'message' => 'Not a valid API request.',
        ]);
    }
}
