<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Package;
use App\Category;
use DataTables;
use Validator;
use Session;
use Auth;
use Flash;
use Redirect;

class PackageController extends Controller
{
    public function list(Request $request)
    {
        return view('Admin.Package.list');
    }
    
    public function datatable(Request $request)
    {
        $Package = Package::get();
        return Datatables::of($Package)
        ->addColumn('action', function($Package) {
            $edit_link = '<a href="'.route('admin.package.edit',$Package->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i> </a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$Package->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i></a>';
            return $edit_link.$delete_link;
        })
        ->editColumn('main_category_id', function($Package) {
            return ($Package->Category) ? $Package->Category->title : '-';
        })
        // ->editColumn('start_date_time', function($Package) {
        //     return ($Package->start_date_time) ? date('l d M Y - H:i',strtotime($Package->start_date_time)) : '-';
        // })
        // ->editColumn('end_date_time', function($Package) {
        //     return ($Package->end_date_time) ? date('l d M Y - H:i',strtotime($Package->end_date_time)) : '-';
        // })
        ->editColumn('time_frame', function($Package) {
            $time_frame = '-';
            if($Package->time_frame == 1)
            {
                $time_frame = '1 Month';
            }
            elseif($Package->time_frame == 2)
            {
                $time_frame = '3 Month';
            }
            elseif($Package->time_frame == 3)
            {
                $time_frame = '6 Month';
            }
            elseif($Package->time_frame == 4)
            {
                $time_frame = '1 Year';
            }
            return $time_frame;
        })
        ->editColumn('is_active', function($Package) {
            $is_active = '-';
            if($Package->is_active == 1)
            {
                $is_active = '<input type="checkbox" data-on-text="&nbsp;&nbsp;Active&nbsp;&nbsp;" data-off-text="Inactive" class="bootstrap-switch" onchange="change_active(this,'.$Package->id.')" checked data-size="normal"/>';
            }
            else
            {
                $is_active = '<input type="checkbox" data-on-text="&nbsp;&nbsp;Active&nbsp;&nbsp;" data-off-text="Inactive" class="bootstrap-switch" onchange="change_active(this,'.$Package->id.')" data-size="normal"/>';
            }
            return ($is_active) ? $is_active : '-';
        })
        ->editColumn('amount', function($Package) {
            return ($Package->amount) ? number_format($Package->amount,2) : '-';
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function add()
    {
        $Category = Category::where('parent_id', 0)->get();
        return view('Admin.Package.add',compact('Category'));
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:package',
            'amount' => 'required|numeric',
            // 'start_date_time' => 'required',
            // 'end_date_time' => 'required',
            'parent_category' => 'required',
            'package_mrp' => 'required',
            'package_offer' => 'required',
            'time_frame' => 'required',
            'about_course_description' => 'required',
            'author_name' => 'required',
            'author_designation' => 'required',
            'author_qualification' => 'required',
            'author_profile_pic' => 'required|mimes:jpeg,jpg,gif,png',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $filename = '';
        if($request->hasFile('author_profile_pic'))
        {
            $file = $request->file('author_profile_pic');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/package_author_profile', $filename);
        }
        $Package = Package::create([
            'title' => $request->title,
            'main_category_id' => $request->parent_category,
            'amount' => $request->amount,
            // 'start_date_time' => date('Y-m-d',strtotime($request->start_date_time)).' 00:00:00',
            // 'end_date_time' => date('Y-m-d',strtotime($request->end_date_time)).' 23:59:59',
            'is_active' => $request->is_active,
            'package_mrp' => $request->package_mrp,
            'package_offer' => $request->package_offer,
            'time_frame' => $request->time_frame,
            'about_course_description' => $request->about_course_description,
            'author_name' => $request->author_name,
            'author_designation' => $request->author_designation,
            'author_qualification' => $request->author_qualification,
            'author_profile_pic' => $filename,
        ]);
        Session::flash('success', 'Package Saved Successful.'); 
        return Redirect()->route('admin.package.list');
    }
    public function edit(Request $request,$id)
    {
        $Category = Category::where('parent_id', 0)->get();
        $Package = Package::where('id',$id)->first();
        return view('Admin.Package.edit',compact('Package','Category'));
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'amount' => 'required|numeric',
            'parent_category' => 'required',
            'package_mrp' => 'required',
            'package_offer' => 'required',
            'time_frame' => 'required',
            'about_course_description' => 'required',
            'author_name' => 'required',
            'author_designation' => 'required',
            'author_qualification' => 'required',
            'author_profile_pic' => 'mimes:jpeg,jpg,gif,png',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        if($request->hasFile('author_profile_pic'))
        {
            $file = $request->file('author_profile_pic');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/package_author_profile', $filename);
            $Package = Package::where('id',$id)->update([
                'author_profile_pic' => $filename,
            ]);
        }
        $Package = Package::where('id',$id)->update([
            'title' => $request->title,
            'amount' => $request->amount,
            'main_category_id' => $request->parent_category,
            // 'start_date_time' => date('Y-m-d',strtotime($request->start_date_time)).' 00:00:00',
            // 'end_date_time' => date('Y-m-d',strtotime($request->end_date_time)).' 23:59:59',
            'is_active' => $request->is_active,
            'package_mrp' => $request->package_mrp,
            'package_offer' => $request->package_offer,
            'time_frame' => $request->time_frame,
            'about_course_description' => $request->about_course_description,
            'author_name' => $request->author_name,
            'author_designation' => $request->author_designation,
            'author_qualification' => $request->author_qualification,
        ]);
        Session::flash('success', 'Package Updated Successful.'); 
        return Redirect()->route('admin.package.list');
    }
    public function delete(Request $request){
        $Package = Package::where('id', $request->id)->delete();
        return response()->json(['succsess'=>true]);
    }
    public function change_active_status(Request $request)
    {
        $Package = Package::where('id',$request->id)->update([
            'is_active' => $request->status,
        ]);
        return response()->json(array('succsess'=>true));
    }
}
