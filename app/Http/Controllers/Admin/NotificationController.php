<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\push_notification;
use App\User;
use Session;
use Auth;
use Validator;
use Redirect;
use Mail;

class NotificationController extends Controller
{
    use push_notification;
    public function addPushNotification()
    {
        $User = User::where('is_admin',0)->where('is_new_register',1)->where('is_block',0)->where('is_verify',1)->get();
        return view('Admin.Notification.add',compact('User'));
    }
    public function sendPushNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|string',
            'description' => 'required',
            'type' => 'required',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        if($request->type =='customised_users')
        {
            $Users = User::where('is_admin',0)->where('is_new_register',1)->whereIn('id',$request->user_id)->get();
        }
        elseif($request->type =='all_users')
        {
            $Users = User::where('is_admin',0)->where('is_new_register',1)->get();
        }
        elseif($request->type =='paid_users')
        {
            $Users = User::where('is_admin',0)->where('is_new_register',1)->where('is_paid',1)->get();
        }
        elseif($request->type =='free_users')
        {
            $Users = User::where('is_admin',0)->where('is_new_register',1)->where('is_paid',0)->get();
        }
        if($Users)
        {
            if(!$Users->isEmpty())
            {
                foreach ($Users as $key => $user) {
                    if($user->device_token != '' && $user->device_type != '')
                    {
                        $notification_array = array(
                            'title' => $request->title,
                            'message' => $request->description,
                            'sound' => 'Default',
                            'badge' => 1,
                            'content_available' => true,
                            'extra_data' => [
                                'title' => $request->title ,
                                'description' => $request->description 
                            ],
                        );
                        $send_notification=$this->notification_push($user->device_token,$notification_array,$user->device_type);
                    }
                }
            }
        }
    	Session::flash('success', 'Notification Send Successful.'); 
    	return Redirect()->route('admin.notification.addPushNotification');
    }
    public function addMailPushNotification()
    {
        $User = User::where('is_admin',0)->where('is_new_register',1)->where('is_block',0)->where('is_verify',1)->get();
        return view('Admin.Notification.add_mail',compact('User'));
    }
    public function sendMailPushNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|string',
            'description' => 'required',
            'type' => 'required',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        if($request->type =='customised_users')
        {
            $Users = User::where('is_admin',0)->where('is_new_register',1)->whereIn('id',$request->user_id)->get();
        }
        elseif($request->type =='all_users')
        {
            $Users = User::where('is_admin',0)->where('is_new_register',1)->get();
        }
        elseif($request->type =='paid_users')
        {
            $Users = User::where('is_admin',0)->where('is_new_register',1)->where('is_paid',1)->get();
        }
        elseif($request->type =='free_users')
        {
            $Users = User::where('is_admin',0)->where('is_new_register',1)->where('is_paid',0)->get();
        }
        if($Users)
        {
            if(!$Users->isEmpty())
            {
                foreach ($Users as $key => $user) {
                    if($user->email != '')
                    {
                        Mail::send('Mail.mail_notification', ['request'=>$request], function($message) use($user,$request) {
                            $message->to($user->email, config('mail.from.name'))->subject($request->title);
                            $message->from(config('mail.from.address'),config('mail.from.name'));
                        });
                    }
                }
            }
        }
        Session::flash('success', 'Notification Mail Send Successful.'); 
        return Redirect()->route('admin.mail_notification.addPushNotification');
    }
}
