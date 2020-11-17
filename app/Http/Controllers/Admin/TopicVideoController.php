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

class TopicVideoController extends Controller
{
    public function list(Request $request)
    {
        return view('Admin.TopicVideo.list');
    }
    public function datatable(Request $request)
    {
        $TopicVideo = TopicVideo::get();
        return Datatables::of($TopicVideo)
        ->editColumn('topic_id', function($TopicVideo) {
            return ($TopicVideo->Topic) ? $TopicVideo->Topic->title : '-';
        })
        ->editColumn('is_paid', function($TopicVideo) {
            return ($TopicVideo->is_paid == 1) ? 'Paid' : 'Free';
        })
        ->addColumn('image', function($TopicVideo) {
            $image = ' - ';
            if($TopicVideo->image!=''){
                $image = '<div class="image-product-div"><img src="'.$TopicVideo->image.'" onerror=this.src="'.asset('No_image_available.png').'" width="100px" class="image-product"></div>';
            }
            return $image;
        })
        ->addColumn('action', function($TopicVideo) {
            $edit_link = '<a href="'.route('admin.topic.video.edit',$TopicVideo->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i> </a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$TopicVideo->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i> </a>';
            return $edit_link.$delete_link;
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function add()
    {
        $Topic = Topic::all();
        return view('Admin.TopicVideo.add',compact('Topic'));
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|max:255',
            'title' => 'required|max:255',
            'is_paid' => 'required',
            'topic_video_image' => 'required|mimes:jpeg,jpg,gif,png',
            'video' => 'required|max:255',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $filename = '';
        if($request->hasFile('topic_video_image'))
        {
            $file = $request->file('topic_video_image');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/topic_video', $filename);
        }
        $TopicVideo = TopicVideo::create([
            'topic_id' => $request->topic,
            'video' => $request->video,
            'image' => $filename,
            'title' => $request->title,
            'is_paid' => $request->is_paid,
        ]);
        Session::flash('success', 'Topic Video Saved Successful.'); 
        return Redirect()->route('admin.topic.video.list');
    }
    public function edit(Request $request,$id)
    {
        $Topic = Topic::all();
        $TopicVideo = TopicVideo::where('id',$id)->first();
        return view('Admin.TopicVideo.edit',compact('Topic','TopicVideo'));
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|max:255',
            'title' => 'required|max:255',
            'is_paid' => 'required',
            'topic_video_image' => 'mimes:jpeg,jpg,gif,png',
            'video' => 'required|max:255',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $TopicVideo = TopicVideo::where('id',$id)->first();
        // $filename = $TopicVideo->image;
        if($request->hasFile('topic_video_image'))
        {
            $file = $request->file('topic_video_image');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/topic_video', $filename);
            $TopicVideo = TopicVideo::where('id',$id)->update([
                'image' => $filename,
            ]);
        }
        $TopicVideo = TopicVideo::where('id',$id)->update([
            'topic_id' => $request->topic,
            'video' => $request->video,
            // 'image' => $filename,
            'title' => $request->title,
            'is_paid' => $request->is_paid,
        ]);
        Session::flash('success', 'Topic Video Update Successful.'); 
        return Redirect()->route('admin.topic.video.list');
    }
    public function delete(Request $request){
        $TopicVideo = TopicVideo::where('id',$request->id)->delete();
        return response()->json(['succsess'=>true]);
    }
}
