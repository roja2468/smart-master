<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Transaction;
use App\Package;
use Session;
use DB;
use Auth;
use Validator;
use Redirect;
use DataTables;

class UserController extends Controller
{
    public function list()
    {
        return view('Admin.User.list');
    }
  	public function edit(Request $request,$id)
    { 
        $category = User::where('id',$id)->first();
        
        return view('Admin.User.edit',compact('category'));
    }
    public function update(Request $request,$id)
    {
      	$id 	=	$request->id;
       	$validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6'
        ]);
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        if($request->password != ""){
          	 $user = User::where('id', $id)->first(); 
          	$token_password 	=	$request->password; 
          $pad 	=	bcrypt($token_password);
          //echo $pad;exit;
            $user->password = $pad;
          	$user->mpassword	=	$token_password;
      //echo "<pre>";print_R($user);exit;
            $user->save();
            Session::flash('message', 'Password Changed Successful.'); 
            Session::flash('class1', 'success'); 
            Session::flash('class', 'success-password');
            return Redirect::to('admin/users');
        }
        else{
            Session::flash('message', 'Please Enter Correct Current Password.'); 
            Session::flash('class1', 'danger'); 
            return Redirect::to('admin/users');
        } 
    }
    public function datatable(Request $request)
    {
        $User = User::query();
        if($request->user_type_filter)
        {
            if($request->user_type_filter == '0_')
            {
                $User->where('is_paid',0);
            }
            else
            {
                $User->where('is_paid',1);
            }
        }
        $User = $User->where('is_admin',0)->where('is_new_register',1)->get();
        return Datatables::of($User)
        ->editColumn('is_paid', function($User) {
            return ($User->is_paid == 1) ? 'Paid' : 'Free';
        })
        ->addColumn('action', function($User) {
            $view_link = '<a href="javascript:void(0);" onclick="view_information(this,'.$User->id.')" data-toggle="tooltip" class="btn  m-r-10 btn-sm btn-success" data-original-title="View Details Of '.$User->f_name.'">View Information</a>';
          	$view_link .= '<a href="'.route('admin.user.edit',$User->id).'" data-toggle="tooltip" class="btn btn-sm btn-warning" data-original-title="Change Password"><i class="fas fa-pencil-alt"></i> </a>';
            return $view_link;
        })
        ->editColumn('is_block', function($User) {
            $is_checked = '';
            if($User->is_block == 1)
            {
                $is_checked = 'checked';
            }
            $is_block = '<input type="checkbox" '.$is_checked.' data-on-text="&nbsp;&nbsp;Block&nbsp;&nbsp;&nbsp;" data-off-text="Unblock" class="bootstrap-switch" id="bootstrap-switch-'.$User->id.'" onchange="change_block(this,'.$User->id.')" data-size="normal" data-old="'.$User->is_block.'"/>';
            return ($is_block) ? $is_block : '-';
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function user_info(Request $request)
    {
    	$User = User::where('id',$request->id)->first();
    	if(!$User)
    	{
    		return response()->json(array('succsess'=>false,'message'=>'User not found.'));
    	}
    	return response()->json(array('succsess'=>true,'message'=>'User found.','user_data'=>$User));
    }
    public function change_block_status(Request $request)
    {
        $User = User::where('id',$request->id)->update([
            'is_block' => $request->status,
        ]);
        return response()->json(array('succsess'=>true));
    }
    public function user_package_list()
    {
        $Transactions = Transaction::withTrashed()->get()->toArray();
        $user_id_arr = array_column($Transactions, 'user_id');
        $User = User::where('is_admin',0)->where('is_new_register',1)->where('is_verify',1)->whereIn('id',$user_id_arr)->get();
        return view('Admin.User.package_user_list',compact('User'));
    }
    public function user_package_datatable(Request $request)
    {
        $Transactions = Transaction::query();
        if($request->user_list)
        {
            $Transactions->where('user_id',$request->user_list);
        }
        if($request->package_type)
        {
            if($request->package_type == 'running')
            {
                $Transactions->where('expiry_date','>',date('Y-m-d H:i:s'))->whereNotNull('expiry_date');
            }
            elseif($request->package_type == 'expired')
            {
                $Transactions->where('expiry_date','<=',date('Y-m-d H:i:s'));
            }
        }
        // ->where('expiry_date','<=',date('Y-m-d H:i:s'))
        $Transactions = $Transactions->withTrashed()->get();
        return Datatables::of($Transactions)
        ->addColumn('f_name', function($Transactions) {
            return ($Transactions->User) ? $Transactions->User->f_name : '-';
        })
        ->addColumn('gender', function($Transactions) {
            return ($Transactions->User) ? $Transactions->User->gender : '-';
        })
        ->addColumn('phone', function($Transactions) {
            return ($Transactions->User) ? $Transactions->User->phone : '-';
        })
        ->addColumn('package_title', function($Transactions) {
            return ($Transactions->Package) ? $Transactions->Package->title : '-';
        })
        ->addColumn('expiry_date', function($Transactions) {
            return ($Transactions->expiry_date) ? date('d M Y h:i A',strtotime($Transactions->expiry_date)) : '-';
        })
        ->addColumn('created_at', function($Transactions) {
            return ($Transactions->created_at) ? date('d M Y h:i A',strtotime($Transactions->created_at)) : '-';
        })
        ->addColumn('status', function($Transactions) {
            if($Transactions->is_expire == 1)
            {
                $status = 'Expired';
            }
            if($Transactions->expiry_date < date('Y-m-d H:i:s'))
            {
                $status = 'Expired';
            }
            else
            {
                $status = 'Running';
            }
            return $status;
        })
        ->addColumn('action', function($Transactions) {
            $username = ($Transactions->User) ? $Transactions->User->f_name : '-';
            $view_link = '<a href="javascript:void(0);" onclick="view_information(this,'.$Transactions->id.')" data-toggle="tooltip" class="btn btn-success" data-original-title="View Details Of '.$username.'">View Information</a>';
            return $view_link;
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function user_package_user_info(Request $request)
    {
        $Transactions = Transaction::where('id',$request->id)->first();
        if(!$Transactions)
        {
            return response()->json(array('succsess'=>false,'message'=>'Not Found Data.'));
        }
        $response_data = array(
            'user_name' => ($Transactions->User) ? $Transactions->User->f_name : '-',
            'phone' => ($Transactions->User) ? $Transactions->User->phone : '-',
            'package_title' => ($Transactions->User) ? $Transactions->Package->title : '-',
            'expiry_date' => ($Transactions->expiry_date) ? date('d M Y h:i A',strtotime($Transactions->expiry_date)) : '-',
            'transaction_id' => ($Transactions->transaction_id) ? $Transactions->transaction_id : '-',
            'order_id' => ($Transactions->order_id) ? $Transactions->order_id : '-',
            'amount' => ($Transactions->amount) ? $Transactions->amount : '-',
        );
        //$package = Package::all();
        $package = DB::select('select * from package');
        return response()->json(array('succsess'=>true,'response_data'=>$response_data,'package'=>$package));
    }
    public function user_remark(Request $request)
    {
        $remark = $request->remark;
        $id = User::find($request->id);
        if($id) 
        {
            $id->remark = $remark;
            $id->save();
        }
        return view('Admin.User.list');

    }
}
