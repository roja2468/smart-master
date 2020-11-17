<?php

namespace App\Http\Middleware;
use App\User;
use Closure;

class APIToken
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
        if($request->mobile_no && $request->mobile_no != '')
        {
            $user = User::where('phone',$request->mobile_no)->first();
            if($user)
            {
                if($user->phone == $request->mobile_no && $user->imei == $request->imei)
                {
                    // if($user->is_verify == 1)
                    // {
                        if($user->is_new_register == 1)
                        {
                            if($user->is_block == 0)
                            {
                                return $next($request);
                            }
                            return response()->json([
                                'status'=>'3',
                                'message' => 'Your account has been block please contact to admin.',
                            ]);
                        }
                        else
                        {
                            return response()->json([
                                'status'=>'1',
                                'message' => 'Your account is not register.',
                            ]);
                        }
                    // }
                    // else
                    // {
                    //     return response()->json([
                    //         'status'=>'2',
                    //         'message' => 'Your account is not verify.',
                    //     ]);
                    // }
                }
                else
                {
                    return response()->json([
                        'status' => '4',
                        'message' => 'Session expired.',
                    ], 200);
                }
            }
            else
            {
                return response()->json([
                    'status'=>'1',
                    'message' => 'Your account is not register.',
                ]);
            }
        }
        return response()->json([
            'status'=>'0',
            'message' => 'Not a valid API request.',
        ]);
    }
}
