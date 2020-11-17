<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\comman_fun;
use Auth;
use Carbon\Carbon;
use App\User;
use App\Slider;
use App\AboutUs;
use App\PrivacyPolicy;
use App\Faq;
use App\Contact;
use App\AppNotification;
use App\AppNotificationRead;
use Str;
use Storage;
use Mail;
use Validator;

class ContentController extends Controller
{
    use comman_fun;
    public function GetBanner(Request $request)
    {
        $Slider = Slider::select('id','image','created_at','updated_at')->where('is_active',1)->get();
        if($Slider->isEmpty())
        {
            return response()->json([
                'status'=>'6',
                'message' => 'No Banner Found.',
            ], 200);
        }
        return response()->json([
            'status'=>'5',
            'message' => 'Banner Data.',
            'data' => $this->setData($Slider->toArray()),
        ], 200);
    }
    public function GetAboutUs()
    {
        $AboutUs = AboutUs::select('id','title','content','created_at','updated_at')->first();
        if(!$AboutUs)
        {
            return response()->json([
                'status'=>'6',
                'message' => 'No About Us Data Found.',
            ], 200);
        }
        return response()->json([
            'status'=>'5',
            'message' => 'About Us Data.',
            'data' => $this->setData($AboutUs->toArray()),
        ], 200);
    }
    public function GetPrivacyPolicy()
    {
        $PrivacyPolicy = PrivacyPolicy::select('id','title','content','created_at','updated_at')->first();
        if(!$PrivacyPolicy)
        {
            return response()->json([
                'status'=>'0',
                'message' => 'No Privacy Policy Data Found.',
            ], 200);
        }
        return response()->json([
            'status'=>'1',
            'message' => 'Privacy Policy Data.',
            'data' => $this->setData($PrivacyPolicy->toArray()),
        ], 200);
    }
    public function GetFaq()
    {
        $Faq = Faq::select('id','title','is_active','content','created_at','updated_at')->get();
        if(!$Faq)
        {
            return response()->json([
                'status'=>'6',
                'message' => 'No Faq Data Found.',
            ], 200);
        }
        return response()->json([
            'status'=>'5',
            'message' => 'Faq Data.',
            'data' => $this->setData($Faq->toArray()),
        ], 200);
    }
    public function PostFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '6',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $Contact = Contact::create([
            'name'=>$request->name,
            'subject'=>$request->subject,
            'message'=>$request->message,
            'email'=>$request->email,
        ]);
        return response()->json([
            'status'=>'5',
            'message' => 'Your feedback sent successfully.',
            'data' => $this->setData($Contact),
        ], 200);
    }
    public function GetAppNotification(Request $request)
    {
        $current_date = date('Y-m-d');
        $user = User::where('phone',$request->mobile_no)->first();
        $AppNotification = AppNotification::where('is_active',1)->where('notification_date','>=',$current_date)->get();
        $AppNotificationIdArr = array_column($AppNotification->toArray(), 'id');
        $AppNotificationReadCount = AppNotificationRead::where('is_read',1)->whereIn('app_notification_id',$AppNotificationIdArr)->where('user_id',$user->id)->count();
        if($AppNotification->isEmpty())
        {
            return response()->json([
                'status'=>'6',
                'message' => 'No App Notification Data Found.',
            ], 200);
        }
        $total_notification_count = $AppNotification->count() - $AppNotificationReadCount;
        return response()->json([
            'status'=>'5',
            'message' => 'App Notification Data.',
            'total_count' => $total_notification_count,
            'data' => $this->setData($AppNotification->toArray()),
        ], 200);
    }
    public function ReadAppNotification(Request $request)
    {
        $current_date = date('Y-m-d');
        $user = User::where('phone',$request->mobile_no)->first();
        $AppNotification = AppNotification::where('is_active',1)->where('notification_date','>=',$current_date)->get();
        if(!$AppNotification->isEmpty())
        {
            foreach ($AppNotification as $key => $Notification) {
                $AppNotificationRead = AppNotificationRead::where('is_read',1)->where('app_notification_id',$Notification->id)->where('user_id',$user->id)->first();
                if(!$AppNotificationRead)
                {
                    AppNotificationRead::create([
                        'user_id' => $user->id,
                        'app_notification_id' => $Notification->id,
                        'is_read' => 1,
                    ]);
                }
            }
        }
        return response()->json([
            'status'=>'5',
            'message' => 'Read successfully.',
        ], 200);
    }
}
