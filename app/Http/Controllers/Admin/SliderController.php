<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Slider;
use DataTables;
use Validator;
use Session;
use Auth;
use Flash;
use Redirect;

class SliderController extends Controller
{
    public function list(Request $request)
    {
        return view('Admin.Slider.list');
    }
    
    public function datatable(Request $request)
    {
        $Slider = Slider::get();
        return Datatables::of($Slider)
        ->addColumn('action', function($Slider) {
            $edit_link = '<a href="'.route('admin.slider.edit',$Slider->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i> </a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$Slider->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i> </a>';
            return $edit_link.$delete_link;
        })
        ->addColumn('image', function($Slider) {
            $image = ' - ';
            if($Slider->image!=''){
                $image = '<div class="image-product-div"><img src="'.$Slider->image.'" onerror=this.src="'.asset('No_image_available.png').'" width="100px" class="image-product"></div>';
            }
            return $image;
        })
        ->editColumn('is_active', function($Slider) {
            $active = '-';
            if($Slider->is_active == 1)
            {
                $active = '<input type="checkbox" class="bootstrap-switch" onchange="change_active(this,'.$Slider->id.')" checked data-size="small"/>';
            }
            else
            {
                $active = '<input type="checkbox" class="bootstrap-switch" onchange="change_active(this,'.$Slider->id.')" data-size="small"/>';
            }
            return ($active) ? $active : '-';
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function add()
    {
        return view('Admin.Slider.add');
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:jpeg,jpg,gif,png',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $filename = '';
        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/slider', $filename);
            $Slider = Slider::create([
                'image' => $filename,
            ]);
        }
        Session::flash('success', 'Slider Saved Successful.'); 
        return Redirect()->route('admin.slider.list');
    }
    public function edit(Request $request,$id)
    {
        $slider = Slider::where('id',$id)->first();
        return view('Admin.Slider.edit',compact('slider'));
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,jpg,gif,png',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $Slider = Slider::where('id',$id)->first();
        $filename = $Slider->image;
        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/slider', $filename);
            $Slider->image = $filename; 
            $Slider->save();
        }
        Session::flash('success', 'Slider Updated Successful.'); 
        return Redirect()->route('admin.slider.list');
    }
    public function delete(Request $request){
        $Slider = Slider::where('id', $request->id)->delete();
        return response()->json(['succsess'=>true]);
    }
    public function change_active_status(Request $request)
    {
        $Slider = Slider::where('id',$request->id)->update([
            'is_active' => $request->status,
        ]);
        return response()->json(array('succsess'=>true));
    }
}
