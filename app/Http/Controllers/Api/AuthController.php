<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\User;
use App\Wallet;
use App\ReferralAmount;
use App\WithdrawAmount;
use Illuminate\Support\Facades\Password;
use App\Notifications\EmailVerificationToken;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;
use App\Traits\comman_fun;
use App\Traits\send_sms;
use Str;
use Validator;

class AuthController extends Controller
{
    use comman_fun;
    use send_sms;
    public function DoRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '0',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->phone)->first();
        $otp = rand(111111,999999);
        if($user)
        {
            $user->otp = $otp;
            $user->save();
        }
        else
        {
            $user = User::create([
                'phone' => $request->phone,
                'otp' => $otp,
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Register Successfully!',
            'user' => $this->setData($user->only(['phone','otp','is_new_register'])),
        ], 200);
    }

    public function DoLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|numeric',
            'device_token' => 'required',
            'password' => 'required|min:8',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '0',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->mobile_no)->first();
        if(!$user)
        {
            // $user = User::create([
            //     'phone' => $request->mobile_no,
            //     'is_new_register' => 0,
            //     'imei' => $request->imei,
            // ]);
            return response()->json([
                'status' => '1',
                'message' => 'Mobile does not exists.',
                // 'user' => $this->setData($user->only(['phone','is_new_register'])),
            ], 200);
        }
       $credentials = array('phone' => $request->mobile_no, 'password' => $request->password); 
      	//dd($credentials);
      //	$user = User::where('mpassword',$request->password)->where('phone',$request->mobile_no)->first();
      	//if(!$user){
          	 if(!Auth::attempt($credentials))
            {
                return response()->json([
                    'status'=>'2',
                    'message' => 'Password Incorrect.'
                ], 200);
            }
        //} 
      	//echo "<pre>";print_R($user);exit;
        if($user->phone == $request->mobile_no && $user->imei == $request->imei)
        {
            // if($user->device_token == '' && ($user->is_new_register == 1 || $user->is_verify == 1))
            // {
            //     $user->device_token = $request->device_token;
            //     $user->save();
            //     return response()->json([
            //         'status' => '3',
            //         'message' => 'OTP has been not verified.',
            //         'user' => $this->setData($user->only(['phone','is_new_register'])),
            //     ], 200);
            // }
            // else if(($user->device_token != '' && $user->device_token != $request->device_token) && ($user->is_new_register == 1 && $user->is_verify == 1))
            // {
            //     return response()->json([
            //         'status' => '3',
            //         'message' => 'OTP has been not verified.',
            //         'user' => $this->setData($user->only(['phone','is_new_register'])),
            //     ], 200);
            // }
            if($user->is_block == 1)
            {
                return response()->json([
                    'status' => '3',
                    'message' => 'Your account has been block please contact to admin.',
                    'user' => $this->setData($user->only(['phone','is_new_register'])),
                ], 200);
            }
            return response()->json([
                'status' => '4',
                'message' => 'Logged in successfully.',
                'user' => $this->setData($user->only(['phone','is_new_register','email','address','gender','profile_pic','created_at','updated_at','education','f_name','dob','is_paid','device_token','imei','device_name','os_version','device_type','referral_code'])),
            ], 200);
            // else if($user->is_new_register == 0 && $user->is_verify == 0)
            // {
            //     return response()->json([
            //         'status' => '1',
            //         'message' => 'Mobile does not exists.',
            //         'user' => $this->setData($user->only(['phone','is_new_register'])),
            //     ], 200);
            // }
            // else if($user->is_verify == 1 && $user->is_new_register == 1)
            // {
            //     return response()->json([
            //         'status' => '2',
            //         'message' => 'Mobile No. has been already verified.',
            //         'user' => $this->setData($user->only(['phone','is_new_register'])),
            //     ], 200);
            // }
            // else if($user->is_new_register == 1 && $user->is_verify == 0)
            // {
            //     return response()->json([
            //         'status' => '3',
            //         'message' => 'OTP has been not verified.',
            //         'user' => $this->setData($user->only(['phone','is_new_register'])),
            //     ], 200);
            // }
            // else if($user->is_new_register == 0 && $user->is_verify == 1)
            // {
            //     return response()->json([
            //         'status' => '4',
            //         'message' => 'OTP verified but not register.',
            //         'user' => $this->setData($user->only(['phone','is_new_register'])),
            //     ], 200);
            // }
            // else
            // {
            //     return response()->json([
            //         'status' => '3',
            //         'message' => 'OTP has been not verified.',
            //         'user' => $this->setData($user->only(['phone','is_new_register'])),
            //     ], 200);
            // }
        }
        else
        {
            $user->imei = $request->imei;
            $user->save();
            return response()->json([
                'status' => '5',
                'message' => 'Session expired.',
                'user' => $this->setData($user->only(['phone','is_new_register'])),
            ], 200);
        }
    }

    public function SendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '0',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->mobile_no)->first();
        $otp = rand(111111,999999);
        if(!$user)
        {
            return response()->json([
                'status' => '1',
                'message' => 'OTP has been not sent. Please try again.'
            ], 200);
        }
        else
        {
            $user->otp = $otp;
            $user->save();
            $message = 'Your otp is '.$otp;
            $sms = $this->sms($user->phone,$message);
        }
        return response()->json([
            'status' => '2',
            'message' => 'OTP has been sent successfully.',
            'user' => $this->setData($user->only(['phone','otp','is_new_register'])),
        ], 200);
    }

    public function VerifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|numeric',
            'otp' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '0',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->mobile_no)->where('otp',$request->otp)->first();
        if(!$user)
        {
            return response()->json([
                'status' => '1',
                'message' => 'OTP has been not verified.Please try again.'
            ], 200);
        }
        $user->is_verify = 1;
        $user->save();
        return response()->json([
            'status' => '2',
            'message' => 'OTP has been verified successfully.',
            'user' => $this->setData($user->only(['phone','otp','is_new_register'])),
        ], 200);
    }

    public function Signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|numeric',
            'password' => 'required|required_with:password_confirmation|same:password_confirmation|min:8',
            // 'email' => 'unique:users'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '0',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $referral_code = $this->generateReferralNumber();
        $user = new User();
        // $user = new User()::where('phone',$request->mobile_no)->first();
        // if(!$user)
        // {
        //     return response()->json([
        //         'status' => '1',
        //         'message' => 'Mobile No. Not exists.'
        //     ], 200);
        // }
        $checkExistEmailCount = User::where('email',$request->email)->count();
        if($checkExistEmailCount > 0)
        {
            return response()->json([
                'status' => '3',
                'message' => 'Email already exist.'
            ], 200);
        }
        $referral_user_id = '';
        // if($request->refer_no)
        // {
        //     $referral_user = User::where('referral_code',$request->refer_no)->first();
        //     if(!$referral_user)
        //     {
        //         return response()->json([
        //             'status' => '4',
        //             'message' => 'Invalid referral code.'
        //         ], 200);
        //     }
        //     if($referral_user->id == $user->id)
        //     {
        //         return response()->json([
        //             'status' => '5',
        //             'message' => 'You can not use your own refer code.'
        //         ], 200);
        //     }
        //     $CheckExistWalletReferralCount = Wallet::where('user_id',$referral_user->id)->where('referral_user_id',$user->id)->count();
        //     if($CheckExistWalletReferralCount > 0)
        //     {
        //         return response()->json([
        //             'status' => '6',
        //             'message' => 'You have already use referral code of this user.'
        //         ], 200);
        //     }
        //     $Wallet = Wallet::create([
        //         'user_id' => $referral_user->id,
        //         'amount' => $this->referralAmount(),
        //         'type' => 1,
        //         'referral_user_id' => $user->id,
        //         'is_referral' => 1,
        //     ]);
        //     $referral_user_id = $referral_user->id;
        // }
        $filename = '';
        if($request->hasFile('profile_pic'))
        {
            $file = $request->file('profile_pic');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/profile_pic', $filename);
            $user->profile_pic = $filename;
        }
        $user->is_new_register = 1;
        $user->f_name = $request->name;
        $user->phone = $request->mobile_no;
        if($request->dob)
        {
        	$user->dob = date('Y-m-d',strtotime($request->dob));
        }
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->password = bcrypt($request->password);
        $user->mpassword = ($request->password);
        $user->education = $request->education;
        $user->imei = $request->imei;
        if(!$user->referral_code)
        {
            $user->referral_code = $referral_code;
        }
        $user->referral_user_id = $referral_user_id;
        $user->save();

        return response()->json([
            'status' => '2',
            'message' => 'Registration has been done successfully.',
            'user' => $this->setData($user->only(['id','profile_pic','f_name','dob','gender','email','address','education','is_verify','referral_code','created_at','updated_at'])),
        ], 200);
    }
    public function logout(Request $request)
    {
    	$user = User::where('phone',$request->mobile_no)->first();
    	$user->device_token = '';
        $user->imei = '';
    	$user->save();
        return response()->json([
            'status'=>'4',
            'message' => 'Logged out successfully.'
        ],200);
    }
    public function updateToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
            'device_type' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '6',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->mobile_no)->first();
        $user->device_token = $request->device_token;
        $user->device_type = $request->device_type;
        $user->save();
        return response()->json([
            'status'=>'5',
            'message' => 'Successfully update data.'
        ],200);
    }
    public function GetReferralAmount(Request $request)
    {
        $user = User::where('phone',$request->mobile_no)->first();
        $Wallets = Wallet::select('id','user_id','amount','type','referral_user_id','created_at')->where('user_id',$user->id)->where('type',1)->get();
        if($Wallets->isEmpty())
        {
            return response()->json([
                'status'=>'5',
                'message' => 'No wallet history found.',
                'refer_amount' => $this->referralAmount(),
                'wallet_amount' => $this->userWalletAmount($user->id),
            ], 200);
        }
        $Wallets = $Wallets->map(function ($Wallet){
            $Wallet->mobile_no = $Wallet->ReferralUser->phone;
            if($Wallet->type == 1)
            {
                $Wallet->type = 'Credit';
            }
            else
            {
                $Wallet->type = 'Debit';
            }
            unset($Wallet->user_id);
            unset($Wallet->referral_user_id);
            unset($Wallet->ReferralUser);
            return $Wallet;
        });
        return response()->json([
            'status'=>'6',
            'message' => 'Wallet Data.',
            'refer_amount' => $this->referralAmount(),
            'wallet_amount' => $this->userWalletAmount($user->id),
            'history' => $this->setData($Wallets->toArray()),
        ], 200);
    }
    public function WithdrawAmount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gpay_mobile_no' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '5',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->mobile_no)->first();
        // $UserWalletAmount = $this->userWalletAmount($user->id);
        if($this->userWalletAmount($user->id) == 0)
        {
            return response()->json([
                'status'=>'6',
                'message' => 'You have insufficient balance.',
                'wallet_amount' => $this->userWalletAmount($user->id),
            ], 200);
        }
        elseif ($this->userWalletAmount($user->id) < $request->amount) {
            return response()->json([
                'status'=>'6',
                'message' => 'You have insufficient balance.',
                'wallet_amount' => $this->userWalletAmount($user->id),
            ], 200);
        }
        $WithdrawAmount = WithdrawAmount::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'gpay_mobile_no' => $request->gpay_mobile_no,
        ]);
        if($WithdrawAmount)
        {
            $Wallet = Wallet::create([
                'user_id' => $WithdrawAmount->user_id,
                'amount' => $WithdrawAmount->amount,
                'type' => 2,
                'gpay_mobile_no' => $request->gpay_mobile_no,
            ]);
        }
        return response()->json([
            'status'=>'7',
            'message' => 'Withdraw amount successfully.',
            'wallet_amount' => $this->userWalletAmount($user->id),
        ], 200);
    }
    public function GetWalletHistory(Request $request)
    {
        $user = User::where('phone',$request->mobile_no)->first();
        $Wallets = Wallet::select('id','user_id','amount','type','referral_user_id','created_at','gpay_mobile_no')->where('user_id',$user->id)->get();
        if($Wallets->isEmpty())
        {
            return response()->json([
                'status'=>'5',
                'message' => 'No wallet history found.',
                'refer_amount' => $this->referralAmount(),
                'wallet_amount' => $this->userWalletAmount($user->id),
            ], 200);
        }
        $Wallets = $Wallets->map(function ($Wallet){
            if($Wallet->type == 1)
            {
                $Wallet->mobile_no = $Wallet->ReferralUser->phone;
                $Wallet->type = 'Credit';
            }
            else
            {
                $Wallet->mobile_no = $Wallet->gpay_mobile_no;
                $Wallet->type = 'Debit';
            }
            unset($Wallet->user_id);
            unset($Wallet->referral_user_id);
            unset($Wallet->gpay_mobile_no);
            unset($Wallet->ReferralUser);
            return $Wallet;
        });
        return response()->json([
            'status'=>'6',
            'message' => 'Wallet History Data.',
            'refer_amount' => $this->referralAmount(),
            'wallet_amount' => $this->userWalletAmount($user->id),
            'history' => $this->setData($Wallets->toArray()),
        ], 200);
    }
    public function ForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '0',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user)
        {
            return response()->json([
                'status' => '1',
                'message' => 'Email ID Not exists.'
            ], 200);
        }
        $token_password = $this->generateRandomPassword();
        $user->password = bcrypt($token_password);
      	$user->mpassword	=	$token_password;
        $user->save();
        $user->notify(
            new PasswordResetRequest($token_password,$user->email)
        );
        return response()->json([
            'status' => '3',
            'message' => 'Successfully mail sent.'
        ], 200);
    }
    public function ChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|min:8',
            'new_password' => 'required|required_with:password_confirmation|same:password_confirmation|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => '5',
                'message' => $validator->messages()->toArray(),
            ], 201);
        }
        $user = User::where('phone',$request->mobile_no)->first();
        if(\Hash::check($request->current_password, $user->password)){
            $user->password = bcrypt($request->new_password);
            $user->save();
            return response()->json([
                'status' => '7',
                'message' => 'Password Change Successfully.',
            ], 201);
        }
        else{
            return response()->json([
                'status' => '6',
                'message' => 'Current password is incorrect.',
            ], 201);
        }
    }
}
