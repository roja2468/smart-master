<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Topic;
use App\TopicDocument;
use App\TopicVideo;
use DataTables;
use Validator;
use Session;
use Auth;
use Flash;
use Redirect;

class TopicController extends Controller
{
    public function list(Request $request)
    {
        return view('Admin.Topic.list');
    }
    public function datatable(Request $request)
    {
        $Topic = Topic::get();
        return Datatables::of($Topic)
        ->editColumn('category_id', function($Topic) {
            return ($Topic->Category) ? $Topic->Category->title : '-';
        })
        ->editColumn('sub_category_id', function($Topic) {
            return ($Topic->subCategory) ? $Topic->subCategory->title : '-';
        })
        ->editColumn('child_category_id', function($Topic) {
            return ($Topic->childCategory) ? $Topic->childCategory->title : '-';
        })
        ->addColumn('action', function($Topic) {
            $edit_link = '<a href="'.route('admin.topic.edit',$Topic->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i> </a>';
            // $test_link = '<a href="'.url('admin/topic/test_insta_mojo').'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i>Payment </a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$Topic->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i> </a>';
            return $edit_link.$delete_link;
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function test_insta_mojo()
    {
        $api = new \Instamojo\Instamojo(
            config('services.instamojo.api_key'),
            config('services.instamojo.auth_token'),
            config('services.instamojo.url')
        );
        try {
            $response = $api->paymentRequestCreate(array(
                "purpose" => "FIFA 16",
                "amount" => '10.00',
                "buyer_name" => "Nitesh",
                "send_email" => false,
                "email" => "nitesh.parmar@gmail.com",
                "phone" => "+918321212121",
                "redirect_url" => url('admin/topic/payment-success')
            ));
            header('Location: ' . $response['longurl']);
            exit();
        }catch (Exception $e) {
            print('Error: ' . $e->getMessage());
        }
    }
    public function success(Request $request){
        try {
            $api = new \Instamojo\Instamojo(
                config('services.instamojo.api_key'),
                config('services.instamojo.auth_token'),
                config('services.instamojo.url')
            );
            $response = $api->paymentRequestStatus($request->payment_request_id);
            if( !isset($response['payments'][0]['status']) ) {
                dd('payment failed');
            } else if($response['payments'][0]['status'] != 'Credit') {
                dd('payment failed');
            } 
        }catch (\Exception $e) {
            dd('payment failed');
        }
        dd($response);
    }
    public function add()
    {
        $parentCategory = Category::where('parent_id',0)->get();
        return view('Admin.Topic.add',compact('parentCategory'));
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_category' => 'required|max:255',
            'sub_category' => 'required|max:255',
            'child_category' => 'required|max:255',
            'title' => 'required|max:255',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $Topic = Topic::create([
            'category_id' => $request->parent_category,
            'sub_category_id' => $request->sub_category,
            'child_category_id' => $request->child_category,
            'title' => $request->title,
        ]);
        Session::flash('success', 'Topic Saved Successful.'); 
        return Redirect()->route('admin.topic.list');
    }
    public function edit(Request $request,$id)
    {
        $Topic = Topic::where('id',$id)->first();
        $parentCategory = Category::where('parent_id',0)->get();
        $subCategory = Category::where('parent_id', $Topic->category_id)->where('sub_parent_id', 0)->get();
        $childCategory = Category::where('parent_id', $Topic->category_id)->where('sub_parent_id', $Topic->sub_category_id)->get();
        return view('Admin.Topic.edit',compact('Topic','subCategory','childCategory','parentCategory'));
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'parent_category' => 'required|max:255',
            'sub_category' => 'required|max:255',
            'child_category' => 'required|max:255',
            'title' => 'required|max:255',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $Topic = Topic::where('id',$id)->update([
            'category_id' => $request->parent_category,
            'sub_category_id' => $request->sub_category,
            'child_category_id' => $request->child_category,
            'title' => $request->title,
        ]);
        Session::flash('success', 'Topic Update Successful.'); 
        return Redirect()->route('admin.topic.list');
    }
    public function delete(Request $request){
        $Topic = Topic::where('id',$request->id)->delete();
        $TopicDocument = TopicDocument::where('topic_id',$request->id)->delete();
        $TopicVideo = TopicVideo::where('topic_id',$request->id)->delete();
        return response()->json(['succsess'=>true]);
    }
    public function document_delete(Request $request)
    {
        $TopicDocument = TopicDocument::where('id',$request->id)->delete();
        return response()->json(['succsess'=>true]);
    }
    public function video_delete(Request $request)
    {
        $TopicVideo = TopicVideo::where('id',$request->id)->delete();
        return response()->json(['succsess'=>true]);
    }
}
