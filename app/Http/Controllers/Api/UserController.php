<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\comman_fun;
use Auth;
use Carbon\Carbon;
use App\User;
use App\Transaction;
use App\Package;
use App\Wallet;
use Str;
use Storage;
use Mail;
use Validator;

class UserController extends Controller
{
    use comman_fun;
    public function GetUserProfile(Request $request)
    {
    	$userProfileData = User::select('id','email','address','gender','city','state','phone','profile_pic','education','f_name','dob','is_paid','device_token','device_type','os_version','device_name','imei','referral_code')->where('phone',$request->mobile_no)->first();
        $referral_code = $this->generateReferralNumber();
        if(!$userProfileData->referral_code)
        {
            $userProfileData->referral_code = $referral_code;
        }
        $userProfileData->save();
        $Transactions = Transaction::where('user_id',$userProfileData->id)->get();
        // dd($Transactions);
        $package_data = array();
        if(!$Transactions->isEmpty())
        {
            foreach ($Transactions as $key => $Transaction) {
                $package = Package::select('id','title','main_category_id')->where('is_active',1)->where('id',$Transaction->package_id)->first();
                if($package)
                {
                    $is_running = 1;
                    if($Transaction->expiry_date && date('Y-m-d H:i',strtotime($Transaction->expiry_date)) < date('Y-m-d H:i'))
                    {
                        $is_running = 0;
                    }
                    $package->category_image = ($package->Category) ? $package->Category->image : asset('no-photo.png');
                    $package->is_running = $is_running;
                    $package_data[$key] = $package;
                    unset($package_data[$key]->Category);
                }
            }
        }
    	return response()->json([
            'status'=>'5',
            'message' => 'User Profile Data.',
            'user_data' => $this->setData($userProfileData->toArray()),
            'package_data' => $package_data
        ], 200);
    }
    public function UpdateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '6',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->mobile_no)->first();
        if($request->hasFile('profile_pic'))
        {
            $file = $request->file('profile_pic');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/profile_pic', $filename);
            $user->profile_pic = $filename;
        }
        $user->is_new_register = 1;
        $user->f_name = $request->name;
        if($request->dob)
        {
            $user->dob = date('Y-m-d',strtotime($request->dob));
        }
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->education = $request->education;
        $user->save();
        return response()->json([
            'status' => '5',
            'message' => 'Profile has been update successfully.',
            'user' => $this->setData($user->only(['id','profile_pic','f_name','dob','gender','email','address','education','is_verify','created_at','updated_at'])),
        ], 200);
    }
    public function GenerateCashfreeOrderToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '5',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $order_id    =   "UPSC".rand(111111,999999).rand(111,999);
        $post_field_arr = array(
            'orderId' => $order_id,
            'orderAmount' => $request->amount,
            'orderCurrency' => 'INR',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,config('services.cashfree.url')."/api/v2/cftoken/order");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($post_field_arr));  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
            'x-client-id: '.config('services.cashfree.app_id'),
            'x-client-secret: '.config('services.cashfree.secret_key'),
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec ($ch);
        curl_close ($ch);
        $response = json_decode($output,true);
        $response['order_id'] = (string)$order_id;
        return response()->json([
            'status' => '6',
            'message' => 'Token Generated.',
            'data' => $this->setData($response),
        ], 200);
    }
    public function OrderCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'transaction_id' => 'required',
            'amount' => 'required|numeric',
            'package_id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '5',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->mobile_no)->first();
        if($request->referral_code)
        {
            $referral_user = User::where('referral_code',$request->referral_code)->first();
            if($referral_user)
            {
                $Wallet = Wallet::create([
                    'user_id' => $referral_user->id,
                    'amount' => $this->referralAmount(),
                    'type' => 1,
                    'referral_user_id' => $user->id,
                    'is_referral' => 1,
                ]);
            }
        }
        $TransactionCount = Transaction::where('user_id',$user->id)->where('package_id',$request->package_id)->first();
        if($TransactionCount)
        {
            if($TransactionCount->expiry_date && date('Y-m-d H:i',strtotime($TransactionCount->expiry_date)) < date('Y-m-d H:i'))
            {
                $TransactionCount->is_expire = 1;
                $TransactionCount->save();
            }
            $TransactionDelete = Transaction::where('user_id',$user->id)->where('package_id',$request->package_id)->delete();
        }
        $package = Package::where('is_active',1)->where('id',$request->package_id)->first();
        if(!$package)
        {
            return response()->json([
                'status' => '6',
                'message' => 'No Package Found.',
            ], 200);
        }
        $headers = [
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
        ];
        $post_field = 'appId='.config('services.cashfree.app_id').'&secretKey='.config('services.cashfree.secret_key').'&orderId='.$request->order_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,config('services.cashfree.url')."/api/v1/order/info/status");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_field);  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec ($ch);
        curl_close ($ch);
        $response = json_decode($output,true);
        if(isset($response['txStatus']) && $response['txStatus'] != 'SUCCESS')
        {
            return response()->json([
                'status' => '7',
                'message' => 'Payment not complete.',
                'status' => $response['txStatus']
            ], 200);
        }
        $expiry_date = Carbon::now();
        if($package->time_frame == 1)
        {
            $package_type = '1 Month';
            $expiry_date->addMonths(1);
        }
        elseif($package->time_frame == 2)
        {
            $package_type = '3 Month';
            $expiry_date->addMonths(3);
        }
        elseif($package->time_frame == 3)
        {
            $package_type = '6 Month';
            $expiry_date->addMonths(6);
        }
        elseif($package->time_frame == 4)
        {
            $package_type = '1 Year';
            $expiry_date->addYears(1);
        }

        $Transaction = Transaction::where('order_id',$request->order_id)->where('transaction_id',$request->transaction_id)->first();
        if($Transaction)
        {
            $Transaction->package_id = $package->id;
            $Transaction->amount = $request->amount;
            $Transaction->expiry_date = $expiry_date->format('Y-m-d').' 23:59:59';
            $Transaction->tnx_response = json_encode($response);
        }
        else
        {
            $Transaction = Transaction::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'transaction_id' => $request->transaction_id,
                'order_id' => $request->order_id,
                'amount' => $request->amount,
                'expiry_date' => $expiry_date->format('Y-m-d').' 23:59:59',
                'tnx_response' => json_encode($response),
            ]);
        }
        $user->is_paid = 1;
        $user->save();
        return response()->json([
            'status' => '8',
            'message' => 'Checkout successfully.',
            'data' => $this->setData($Transaction->toArray()),
        ], 200);
    }
    public function GetMyPackage(Request $request)
    {
        $user = User::where('phone',$request->mobile_no)->first();
        $Transactions = Transaction::where('user_id',$user->id)->get();
        // ->where('expiry_date','>',date('Y-m-d H:i:s'))
        $package_data = array();
        if($Transactions->isEmpty())
        {
            return response()->json([
                'status' => '5',
                'message' => 'Not exist any package.',
            ], 200);
        }
        foreach ($Transactions as $key => $Transaction) {
            $package = Package::select('id','title','amount','main_category_id','package_mrp','package_offer','about_course_description','author_name','author_profile_pic','author_designation','author_qualification','time_frame','end_date_time','created_at','updated_at')->where('is_active',1)
            ->with(['Category'=>function($query){
                $query->select(['id','title','image','description','is_active','created_at','updated_at']);
            },'DemoArticle'=>function($query){
                $query->select(['id','package_id','image','file','is_active','created_at','updated_at']);
            },'DemoVideo'=>function($query){
                $query->select(['id','package_id','image','file','is_active','created_at','updated_at']);
            }])->where('id',$Transaction->package_id)->first();
            if($package)
            {
                $topic_ids = $package->topic->pluck('id')->toArray();
                $topic_video_count = \DB::table('topic_video')->selectRaw('count(id) as topic_video_count')->whereIn('topic_id', $topic_ids)->whereNull('deleted_at')->first();
                $package->topic_video_count = $topic_video_count->topic_video_count;

                $topic_document_count = \DB::table('topic_document')->selectRaw('count(id) as topic_document_count')->whereIn('topic_id', $topic_ids)->whereNull('deleted_at')->first();
                $package->topic_document_count = $topic_document_count->topic_document_count;
                $package_type = '';
                if($package->time_frame == 1)
                {
                    $package_type = '1 Month';
                }
                elseif($package->time_frame == 2)
                {
                    $package_type = '3 Month';
                }
                elseif($package->time_frame == 3)
                {
                    $package_type = '6 Month';
                }
                elseif($package->time_frame == 4)
                {
                    $package_type = '1 Year';
                }
                $is_running = 1;
                if($Transaction->expiry_date && date('Y-m-d H:i',strtotime($Transaction->expiry_date)) < date('Y-m-d H:i'))
                {
                    $is_running = 0;
                }
                $package->is_running = $is_running;
                $package->end_date_time = $Transaction->expiry_date;
                $package->package_type = $package_type;
                unset($package->topic);
                unset($package->time_frame);
                $package_data[$key] = $package;
            }
        }
        return response()->json([
            'status' => '6',
            'message' => 'Your package data.',
            'data' => $this->setData(array_values($package_data)),
        ], 200);
    }
    public function CronOfUserPaidFree()
    {
        $Users = User::where('is_admin',0)->where('is_new_register',1)->get();
        if(!$Users->isEmpty())
        {
            foreach ($Users as $key => $User) {
                $is_paid = 0;
                $buy_packages_count = Transaction::where('user_id',$User->id)->where('expiry_date','>',date('Y-m-d H:i:s'))->count();
                if($buy_packages_count > 0)
                {
                    $is_paid = 1;
                }
                User::where('id',$User->id)->update(['is_paid'=>$is_paid]);
            }
        }
    }
    public function IgnorePackageProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '5',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->mobile_no)->first();
        $Transaction = Transaction::where('user_id',$user->id)->where('package_id',$request->package_id)->first();
        if(!$Transaction)
        {
            return response()->json([
                'status' => '6',
                'message' => 'No transaction data found.' 
            ], 200);
        }
        if($Transaction->expiry_date && (date('Y-m-d',strtotime($Transaction->expiry_date)) != date('Y-m-d') || date('Y-m-d',strtotime($Transaction->expiry_date)) > date('Y-m-d')))
        {
            return response()->json([
                'status' => '7',
                'message' => 'This package is already running.' 
            ], 200);
        }
        $Transaction = Transaction::where('user_id',$user->id)->where('package_id',$request->package_id)->delete();
        return response()->json([
            'status' => '8',
            'message' => 'Ignore successfully.' 
        ], 200);
    }
    public function GetReferralCodeCommission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|numeric',
            'main_category_id' => 'required|numeric',
            // 'referral_code' => 'required',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '5',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->mobile_no)->first();
        $package = Package::where('is_active',1)->where('id',$request->package_id)->where('main_category_id',$request->main_category_id)->first();
        if(!$package)
        {
            return response()->json([
                'status' => '6',
                'message' => 'No Package Found.'
            ], 200);
        }
        $referral_user = User::where('referral_code',$request->referral_code)->whereNotNull('referral_code')->first();
        if(!$referral_user)
        {
            return response()->json([
                'status' => '7',
                'message' => 'Invalid referral code.'
            ], 200);
        }
        if($referral_user->id == $user->id)
        {
            return response()->json([
                'status' => '8',
                'message' => 'You can not use your own refer code.'
            ], 200);
        }
        $CheckExistWalletReferralCount = Wallet::where('user_id',$referral_user->id)->where('referral_user_id',$user->id)->count();
        if($CheckExistWalletReferralCount > 0)
        {
            return response()->json([
                'status' => '9',
                'message' => 'You have already use referral code of this user.'
            ], 200);
        }
        // $Wallet = Wallet::create([
        //     'user_id' => $referral_user->id,
        //     'amount' => $this->referralAmount(),
        //     'type' => 1,
        //     'referral_user_id' => $user->id,
        //     'is_referral' => 1,
        // ]);
        $referral_user_id = $referral_user->id;
        $referral_code_commission_amount = $this->referralCommissionAmount();
        $package_price = ($package->amount) ? $package->amount : 0;
        $final_price = $package_price - $referral_code_commission_amount;
        return response()->json([
            'status' => '10',
            'message' => 'Referral code applied successfully.',
            'package_price' => $package_price,
            'final_price' => $final_price,
            'referral_code_commission_amount' => $referral_code_commission_amount,
        ], 200);
    }
    public function GetExpirePackage(Request $request)
    {
        $user = User::where('phone',$request->mobile_no)->first();
        $Transactions = Transaction::where('user_id',$user->id)->where('expiry_date','<',date('Y-m-d H:i:s'))->orWhere('is_expire',1)->get();
        if($Transactions->isEmpty())
        {
            return response()->json([
                'status' => '5',
                'message' => 'No expire package data found.'
            ], 200);
        }
        $response_data = array();
        foreach ($Transactions as $key => $Transaction) {
            $response_data[$key]['package_id'] = ($Transaction->Package) ? $Transaction->Package->id : '';
            $response_data[$key]['package_name'] = ($Transaction->Package) ? $Transaction->Package->title : '';
            $response_data[$key]['package_price'] = ($Transaction->Package) ? $Transaction->Package->amount : '';
            $response_data[$key]['package_mrp'] = ($Transaction->Package) ? $Transaction->Package->package_mrp : '';
            $response_data[$key]['package_offer'] = ($Transaction->Package) ? $Transaction->Package->package_offer : '';
            $response_data[$key]['package_expiry_date'] = ($Transaction->expiry_date) ? $Transaction->expiry_date : '';
            $response_data[$key]['main_category_id'] = ($Transaction->Package) ? $Transaction->Package->main_category_id : '';
            $response_data[$key]['category_name'] = ($Transaction->Package) ? ($Transaction->Package->Category) ? $Transaction->Package->Category->title  : '' : '';
            $response_data[$key]['category_image'] = ($Transaction->Package) ? ($Transaction->Package->Category) ? $Transaction->Package->Category->image  : '' : '';
            $package_type = '';
            $expiry_date = Carbon::now();
            if($Transaction->Package->time_frame == 1)
            {
                $package_type = '1 Month';
                $expiry_date->addMonths(1);
            }
            elseif($Transaction->Package->time_frame == 2)
            {
                $package_type = '3 Month';
                $expiry_date->addMonths(3);
            }
            elseif($Transaction->Package->time_frame == 3)
            {
                $package_type = '6 Month';
                $expiry_date->addMonths(6);
            }
            elseif($Transaction->Package->time_frame == 4)
            {
                $package_type = '1 Year';
                $expiry_date->addYears(1);
            }
            $response_data[$key]['end_date_time'] = $expiry_date->format('Y-m-d').' 23:59:59';
            $response_data[$key]['package_type'] = $package_type;
        }
        return response()->json([
            'status' => '6',
            'message' => 'expire package data.',
            'data' => $this->setData(array_values($response_data)),
        ], 200);
    }
}
