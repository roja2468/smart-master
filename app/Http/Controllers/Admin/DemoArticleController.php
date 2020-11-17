<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DemoArticle;
use App\Package;
use DataTables;
use Validator;
use Session;
use Auth;
use Flash;
use Redirect;

class DemoArticleController extends Controller
{
    public function list(Request $request)
    {
        return view('Admin.DemoArticle.list');
    }
    public function datatable(Request $request)
    {
        $DemoArticle = DemoArticle::get();
        return Datatables::of($DemoArticle)
        ->editColumn('image', function($DemoArticle) {
            $image = ' - ';
            if($DemoArticle->image!=''){
                $image = '<div class="image-product-div"><img src="'.$DemoArticle->image.'" onerror=this.src="'.asset('No_image_available.png').'" width="100px" class="image-product"></div>';
            }
            return $image;
        })
        ->addColumn('action', function($DemoArticle) {
            $edit_link = '<a href="'.route('admin.demo.article.edit',$DemoArticle->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i> </a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$DemoArticle->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i> </a>';
            return $edit_link.$delete_link;
        })
        ->editColumn('package_id', function($DemoArticle) {
            return ($DemoArticle->Package) ? $DemoArticle->Package->title : '-';
        })
        ->editColumn('is_active', function($DemoArticle) {
            $active = '-';
            if($DemoArticle->is_active == 1)
            {
                $active = '<input type="checkbox" class="bootstrap-switch" onchange="change_active(this,'.$DemoArticle->id.')" checked data-size="small"/>';
            }
            else
            {
                $active = '<input type="checkbox" class="bootstrap-switch" onchange="change_active(this,'.$DemoArticle->id.')" data-size="small"/>';
            }
            return ($active) ? $active : '-';
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function add()
    {
        $Packages = Package::where('is_active',1)->get();
        return view('Admin.DemoArticle.add',compact('Packages'));
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image.*' => 'required|mimes:jpg,jpeg,png,bmp|max:5000',
            'file.*' => 'required|max:20000',
        ],[
            'image.*.required' => 'Please upload an image',
            'image.*.mimes' => 'Only jpeg,png and bmp images are allowed',
            'image.*.max' => 'Sorry! Maximum allowed size for an image is 5MB',
            'file.*.required' => 'Please upload an image',
            'file.*.max' => 'Sorry! Maximum allowed size for an file is 20MB',
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
                $file->move(public_path().'/uploads/demo_article', $filename);
                $file_array[$key]['image'] = $filename;
            }
        }
        if($request->hasFile('file'))
        {
            $files = $request->file('file');
            foreach ($files as $key => $file) {
                $filename = time().'_'.trim($file->getClientOriginalName());
                $file->move(public_path().'/uploads/demo_article', $filename);
                $file_array[$key]['file'] = $filename;
            }
        }
        if(!empty($file_array))
        {
            foreach ($file_array as $key => $file) {
                $DemoArticle = DemoArticle::create([
                    'package_id' => $request->package_id,
                    'image' => $file['image'],
                    'file' => $file['file'],
                ]);
            }
        }
        Session::flash('success', 'Demo Article Saved Successful.'); 
        return Redirect()->route('admin.demo.article.list');
    }
    public function edit(Request $request,$id)
    {
        $Packages = Package::where('is_active',1)->get();
        $DemoArticle = DemoArticle::where('id',$id)->first();
        return view('Admin.DemoArticle.edit',compact('DemoArticle','Packages'));
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpg,jpeg,png,bmp|max:5000',
            'file' => 'max:20000',
        ],[
            'image.mimes' => 'Only jpeg,png and bmp images are allowed',
            'image.max' => 'Sorry! Maximum allowed size for an image is 5MB',
            'file.max' => 'Sorry! Maximum allowed size for an file is 20MB',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/demo_article', $filename);
            $DemoArticle = DemoArticle::where('id',$id)->update([
                'image' => $filename,
            ]);
        }
        if($request->hasFile('file'))
        {
            $file = $request->file('file');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/demo_article', $filename);
            $DemoArticle = DemoArticle::where('id',$id)->update([
                'file' => $filename,
            ]);
        }
        $DemoArticle = DemoArticle::where('id',$id)->update([
            'package_id' => $request->package_id,
            'is_active' => $request->is_active,
        ]);
        Session::flash('success', 'Demo Article Update Successful.'); 
        return Redirect()->route('admin.demo.article.list');
    }
    public function delete(Request $request){
        $DemoArticle = DemoArticle::where('id',$request->id)->delete();
        return response()->json(['succsess'=>true]);
    }
    public function change_active_status(Request $request)
    {
        $DemoArticle = DemoArticle::where('id',$request->id)->update([
            'is_active' => $request->status,
        ]);
        return response()->json(array('succsess'=>true));
    }
}
