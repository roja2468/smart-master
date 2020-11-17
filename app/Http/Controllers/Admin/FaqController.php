<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Faq;
use DataTables;
use Validator;
use Session;
use Auth;
use Flash;
use Redirect;

class FaqController extends Controller
{
    public function list(Request $request)
    {
        return view('Admin.Faq.list');
    }
    public function datatable(Request $request)
    {
        $Faq = Faq::get();
        return Datatables::of($Faq)
        ->addColumn('action', function($Faq) {
            $edit_link = '<a href="'.route('admin.faq.edit',$Faq->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i> </a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$Faq->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i> </a>';
            return $edit_link.$delete_link;
        })
        ->editColumn('content', function($Faq) {
            $active = \Str::limit(strip_tags($Faq->content),30);

            return $active;
        })
        ->editColumn('is_active', function($Faq) {
            $active = '-';
            if($Faq->is_active == 1)
            {
                $active = '<input type="checkbox" class="bootstrap-switch" onchange="change_active(this,'.$Faq->id.')" checked data-size="small"/>';
            }
            else
            {
                $active = '<input type="checkbox" class="bootstrap-switch" onchange="change_active(this,'.$Faq->id.')" data-size="small"/>';
            }
            return ($active) ? $active : '-';
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function add()
    {
        return view('Admin.Faq.add');
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required|max:255',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $Faq = Faq::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->is_active,
        ]);
        Session::flash('success', 'Faq Saved Successful.'); 
        return Redirect()->route('admin.faq.list');
    }
    public function edit(Request $request,$id)
    {
        $Faq = Faq::where('id',$id)->first();
        return view('Admin.Faq.edit',compact('Faq'));
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required|max:255',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $Faq = Faq::where('id',$id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->is_active,
        ]);
        Session::flash('success', 'Faq Update Successful.'); 
        return Redirect()->route('admin.faq.list');
    }
    public function delete(Request $request){
        $Faq = Faq::where('id',$request->id)->delete();
        return response()->json(['succsess'=>true]);
    }
    public function change_active_status(Request $request)
    {
        $Faq = Faq::where('id',$request->id)->update([
            'is_active' => $request->status,
        ]);
        return response()->json(array('succsess'=>true));
    }
}
