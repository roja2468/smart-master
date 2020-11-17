<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DemoVideo;
use App\Package;
use DataTables;
use Validator;
use Session;
use Auth;
use Flash;
use Redirect;

class DemoVideoController extends Controller
{
    public function list(Request $request)
    {
        return view('Admin.DemoVideo.list');
    }
    public function datatable(Request $request)
    {
        $DemoVideo = DemoVideo::get();
        return Datatables::of($DemoVideo)
        ->editColumn('image', function($DemoVideo) {
            $image = ' - ';
            if($DemoVideo->image!=''){
                $image = '<div class="image-product-div"><img src="'.$DemoVideo->image.'" onerror=this.src="'.asset('No_image_available.png').'" width="100px" class="image-product"></div>';
            }
            return $image;
        })
        ->addColumn('action', function($DemoVideo) {
            $edit_link = '<a href="'.route('admin.demo.video.edit',$DemoVideo->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i> </a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$DemoVideo->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i> </a>';
            return $edit_link.$delete_link;
        })
        ->editColumn('package_id', function($DemoVideo) {
            return ($DemoVideo->Package) ? $DemoVideo->Package->title : '-';
        })
        ->editColumn('is_active', function($DemoVideo) {
            $active = '-';
            if($DemoVideo->is_active == 1)
            {
                $active = '<input type="checkbox" class="bootstrap-switch" onchange="change_active(this,'.$DemoVideo->id.')" checked data-size="small"/>';
            }
            else
            {
                $active = '<input type="checkbox" class="bootstrap-switch" onchange="change_active(this,'.$DemoVideo->id.')" data-size="small"/>';
            }
            return ($active) ? $active : '-';
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function add()
    {
        $Packages = Package::where('is_active',1)->get();
        return view('Admin.DemoVideo.add',compact('Packages'));
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image.*' => 'required|mimes:jpg,jpeg,png,bmp|max:5000',
            'file.*' => 'required',
        ],[
            'image.*.required' => 'Please upload an image',
            'image.*.mimes' => 'Only jpeg,png and bmp images are allowed',
            'image.*.max' => 'Sorry! Maximum allowed size for an image is 5MB',
            'file.*.required' => 'Please upload an image',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $filename = '';
        $file_array = array();
        if($request->hasFile('image'))
        {
            $files = $request->file('image');
            foreach ($files as $key => $file) {
                $filename = time().'_'.trim($file->getClientOriginalName());
                $file->move(public_path().'/uploads/demo_video', $filename);
                $file_array[$key]['image'] = $filename;
                $file_array[$key]['file'] = $request->file[$key];
            }
        }
        if(!empty($file_array))
        {
            foreach ($file_array as $key => $file) {
                $DemoVideo = DemoVideo::create([
                    'package_id' => $request->package_id,
                    'image' => $file['image'],
                    'file' => $file['file'],
                ]);
            }
        }
        Session::flash('success', 'Demo Video Saved Successful.'); 
        return Redirect()->route('admin.demo.video.list');
    }
    public function edit(Request $request,$id)
    {
        $Packages = Package::where('is_active',1)->get();
        $DemoVideo = DemoVideo::where('id',$id)->first();
        return view('Admin.DemoVideo.edit',compact('DemoVideo','Packages'));
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpg,jpeg,png,bmp|max:5000',
        ],[
            'image.mimes' => 'Only jpeg,png and bmp images are allowed',
            'image.max' => 'Sorry! Maximum allowed size for an image is 5MB',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/demo_video', $filename);
            $DemoVideo = DemoVideo::where('id',$id)->update([
                'image' => $filename,
            ]);
        }
        $DemoVideo = DemoVideo::where('id',$id)->update([
            'package_id' => $request->package_id,
            'file' => $request->file,
            'is_active' => $request->is_active,
        ]);
        Session::flash('success', 'Demo Video Update Successful.'); 
        return Redirect()->route('admin.demo.video.list');
    }
    public function delete(Request $request){
        $DemoVideo = DemoVideo::where('id',$request->id)->delete();
        return response()->json(['succsess'=>true]);
    }
    public function change_active_status(Request $request)
    {
        $DemoVideo = DemoVideo::where('id',$request->id)->update([
            'is_active' => $request->status,
        ]);
        return response()->json(array('succsess'=>true));
    }
}
