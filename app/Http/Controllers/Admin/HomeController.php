<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Topic;
use App\TopicDocument;
use App\TopicVideo;
use App\Comment;

use Session;
use Auth;
use Validator;
use Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $Topic = Topic::count();
        $TopicVideo = TopicVideo::count();
        $TopicDocument = TopicDocument::count();
        $PaidUser = User::where('is_admin',0)->where('is_paid',1)->where('is_new_register',1)->count();
        $FreeUser = User::where('is_admin',0)->where('is_paid',0)->where('is_new_register',1)->count();
        $RecentComment = Comment::limit(5)->orderBy('created_at','DESC')->get();
        return view('Admin.dashboard',compact('Topic','TopicVideo','TopicDocument','RecentComment','PaidUser','FreeUser'));
    }
    public function profile()
    {
        return view('Admin.profile');
    }
    
    public function update_profile(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255|string',
            'last_name' => 'required|max:255|string',
            'email' => 'required|email',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        User::where('id',Auth::user()->id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_1' => $request->phone,
        ]);
        Session::flash('message', 'Profile Update Successful.'); 
        Session::flash('class', 'success'); 
        return Redirect::to('admin/profile');
    }
    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:8',
            'old_password' => 'required|min:8'
        ]);
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $user = Auth::user();
        if(\Hash::check($request->old_password, $user->password)){
            $user->password = bcrypt($request->password);
            $user->save();
            Session::flash('message', 'Password Changed Successful.'); 
            Session::flash('class1', 'success'); 
            Session::flash('class', 'success-password');
            return Redirect::to('admin/profile');
        }
        else{
            Session::flash('message', 'Please Enter Correct Current Password.'); 
            Session::flash('class1', 'danger'); 
            return Redirect::to('admin/profile');
        }
    }
    public function test_payment()
    {
        $secretKey = "4dcf1b1dcbe4c019cd6052e7691db8d7a8709e21";
        $postData = array( 
            "appId" => '21010681dcd7ab378a42b948001012', 
            "orderId" => 'order00001', 
            "orderAmount" => '100', 
            "orderCurrency" => 'INR', 
            "orderNote" => 'test', 
            "customerName" => 'John Doe', 
            "customerPhone" => '9999999999', 
            "customerEmail" => 'Johndoe@test.com',
            "returnUrl" => url('payment-success'), 
            "notifyUrl" => url('payment-notify'),
        );
        ksort($postData);
        $signatureData = "";
        foreach ($postData as $key => $value){
            $signatureData .= $key.$value;
        }
        $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
        $signature = base64_encode($signature);
        return view('cashfree_payment_getway',compact('signature','postData'));
    }
}
