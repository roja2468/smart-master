<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected function authenticated(Request $request,$user)
    {
        if($user->is_admin == 1)
        {
            return redirect('admin/dashboard');
        }
        else
        {
            $this->guard()->logout();
            $request->session()->flush();
            Session::flash('message', 'You are not allow to login.');
            Session::flash('alert-class', 'alert-danger');
            return redirect('/admin');
        }
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = 'home';
    public function redirectTo(){
        return 'admin/dashboard';
    }

    // public function username()
    // {
    //     return 'email';
    // }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect(route('admin.login'));
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
