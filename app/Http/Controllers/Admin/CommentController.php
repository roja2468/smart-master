<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Comment;
use Session;
use Auth;
use Validator;
use Redirect;
use DataTables;

class CommentController extends Controller
{
    public function list()
    {
        return view('Admin.Comment.list');
    }
    public function datatable(Request $request)
    {
        $Comment = Comment::get();
        return Datatables::of($Comment)
        ->editColumn('user_id', function($Comment) {
            return ($Comment->User) ? $Comment->User->f_name : '-';
        })
        ->editColumn('type', function($Comment) {
            return ($Comment->type == 0) ? 'Video' : 'Document';
        })
        ->editColumn('comment_for_id', function($Comment) {
        	$comment_for = '-';
        	if($Comment->type == 0)
        	{
        		$comment_for = ($Comment->TopicVideo) ? $Comment->TopicVideo->title : '-';
        	}
        	else
        	{
        		$comment_for = ($Comment->TopicDocument) ? $Comment->TopicDocument->title : '-';
        	}
            return ($comment_for) ? $comment_for : '-';
        })
        ->editColumn('created_at', function($Comment) {
            return ($Comment->created_at) ? date('d-m-Y h:i A',strtotime($Comment->created_at)) : '-';
        })
        ->editColumn('is_approve', function($Comment) {
        	$approve = '-';
        	if($Comment->is_approve == 1)
        	{
        		$approve = '<input type="checkbox" class="bootstrap-switch" onchange="change_approve(this,'.$Comment->id.')" checked data-size="small"/>';
        	}
        	else
        	{
        		$approve = '<input type="checkbox" class="bootstrap-switch" onchange="change_approve(this,'.$Comment->id.')" data-size="small"/>';
        	}
            return ($approve) ? $approve : '-';
        })
        ->addColumn('action', function($Comment) {
            $view_link = '<a href="javascript:void(0);" onclick="view_information(this,'.$Comment->id.')" data-toggle="tooltip" class="btn btn-success" data-original-title="View Details">View Information</a>';
            return $view_link;
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function change_approve_status(Request $request)
    {
    	$comment = Comment::where('id',$request->id)->update([
    		'is_approve' => $request->status,
    	]);
    	return response()->json(array('succsess'=>true));
    }
    public function comment_info(Request $request)
    {
    	$Comment = Comment::where('id',$request->id)->first();
    	if(!$Comment)
    	{
    		return response()->json(array('succsess'=>false,'message'=>'Comment not found.'));
    	}
    	$comment_for = '-';
    	if($Comment->type == 0)
    	{
    		$comment_for = ($Comment->TopicVideo) ? $Comment->TopicVideo->title : '-';
    	}
    	else
    	{
    		$comment_for = ($Comment->TopicDocument) ? $Comment->TopicDocument->title : '-';
    	}
    	$approve = '-';
    	if($Comment->is_approve == 1)
    	{
    		$approve = 'Approved';
    	}
    	else
    	{
    		$approve = 'Not Approved';
    	}
    	$comment_arr = array(
    		'user_name' =>  $Comment->User->f_name,
    		'comment_for_type' =>  ($Comment->type == 0) ? 'Video' : 'Document',
    		'comment_for' =>  $comment_for,
    		'created_at' =>  date('d-m-Y h:i A',strtotime($Comment->created_at)),
    		'approve' =>  $approve,
    	);
    	return response()->json(array('succsess'=>true,'message'=>'Comment found.','comment_data'=>$comment_arr));
    }
}
