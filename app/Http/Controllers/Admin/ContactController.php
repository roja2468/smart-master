<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Contact;
use Session;
use Auth;
use Validator;
use Redirect;
use DataTables;

class ContactController extends Controller
{
    public function list()
    {
        return view('Admin.Contact.list');
    }
    public function datatable(Request $request)
    {
        $Contact = Contact::get();
        return Datatables::of($Contact)
        ->addColumn('action', function($Contact) {
            $view_link = '<a href="javascript:void(0);" onclick="view_information(this,'.$Contact->id.')" data-toggle="tooltip" class="btn btn-success" data-original-title="View Details">View Information</a>';
            return $view_link;
        })
        ->editColumn('created_at', function($Comment) {
            return ($Comment->created_at) ? date('d-m-Y h:i A',strtotime($Comment->created_at)) : '-';
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function contact_info(Request $request)
    {
    	$Contact = Contact::where('id',$request->id)->first();
    	if(!$Contact)
    	{
    		return response()->json(array('succsess'=>false,'message'=>'Contact not found.'));
    	}
    	$date_create = date('d-m-Y h:i A',strtotime($Contact->created_at));
    	return response()->json(array('succsess'=>true,'message'=>'Contact found.','contact_data'=>$Contact,'date_create'=>$date_create));
    }
}
