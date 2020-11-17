<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PrivacyPolicy;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return "<center><h1>Password Reset Successfully.</h1></center>";
        // return view('home');
    }
    public function privacy_policy()
    {
        $PrivacyPolicy = PrivacyPolicy::first();
        return view('privacy_policy',compact('PrivacyPolicy'));
    }
    public function guidlines()
    {
        return view('guidlines');
    }
  public function refund()
    {
        return view('refund');
    }
}
