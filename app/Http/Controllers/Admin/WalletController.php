<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Wallet;
use App\ReferralAmount;
use App\WithdrawAmount;
use Session;
use Auth;
use Validator;
use Redirect;
use DataTables;

class WalletController extends Controller
{
    public function list()
    {
        return view('Admin.Wallet.list');
    }
    public function datatable(Request $request)
    {
        $WithdrawAmount = WithdrawAmount::get();
        return Datatables::of($WithdrawAmount)
        ->editColumn('is_approve', function($WithdrawAmount) {
            $is_approve = '-';
            if($WithdrawAmount->is_approve == 0)
            {
                $is_approve = '<input type="checkbox" data-on-text="&nbsp;&nbsp;Approve&nbsp;&nbsp;&nbsp;" data-off-text="Disapprove" class="bootstrap-switch" id="bootstrap-switch-'.$WithdrawAmount->id.'" onchange="change_approve(this,'.$WithdrawAmount->id.')" data-size="normal" data-old="'.$WithdrawAmount->is_approve.'"/>';
            }
            return $is_approve;
        })
        ->addColumn('u_name', function($WithdrawAmount) {
            return ($WithdrawAmount->User) ? $WithdrawAmount->User->f_name : '-';
        })
        ->addColumn('u_phone', function($WithdrawAmount) {
            return ($WithdrawAmount->User) ? $WithdrawAmount->User->phone : '-';
        })
        ->editColumn('amount', function($WithdrawAmount) {
            return ($WithdrawAmount->amount) ? number_format($WithdrawAmount->amount) : '-';
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function change_approve_status(Request $request)
    {
        $WithdrawAmount = WithdrawAmount::find($request->id);
        if($WithdrawAmount)
        {
            // $Wallet = Wallet::create([
            //     'user_id' => $WithdrawAmount->user_id,
            //     'amount' => $WithdrawAmount->amount,
            //     'type' => 2,
            //     'referral_user_id' => $WithdrawAmount->gpay_user_id,
            // ]);
            $WithdrawAmount->is_approve = 1;
            $WithdrawAmount->save();
            return response()->json(array('succsess'=>true,'message'=>'Request Approved.'));
        }
        return response()->json(array('succsess'=>false,'message'=>'Request Not Approved.'));
    }
    public function referral_amount_edit()
    {
        $ReferralAmount = ReferralAmount::find(1);
        return view('Admin.Wallet.referral_amount',compact('ReferralAmount'));
    }
    public function referral_amount_update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $ReferralAmount = ReferralAmount::where('id',$id)->update([
            'amount' => $request->amount,
        ]);
        Session::flash('success', 'Referral Amount Updated Successful.'); 
        return Redirect()->route('admin.referral_amount.edit');
    }
    public function referral_amount_commission_edit()
    {
        $ReferralAmount = ReferralAmount::find(1);
        return view('Admin.Wallet.referral_amount_commission',compact('ReferralAmount'));
    }
    public function referral_amount_commission_update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $ReferralAmount = ReferralAmount::where('id',$id)->update([
            'referral_amount_commission' => $request->amount,
        ]);
        Session::flash('success', 'Referral Amount Commission Updated Successful.'); 
        return Redirect()->route('admin.referral_amount_commission.edit');
    }
}
