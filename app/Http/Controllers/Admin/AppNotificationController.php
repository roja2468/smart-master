<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AppNotification;
use DataTables;
use Validator;
use Session;
use Auth;
use Flash;
use Redirect;

class AppNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin.AppNotification.list');
    }
    public function datatable(Request $request)
    {
        $AppNotification = AppNotification::get();
        return Datatables::of($AppNotification)
        ->addColumn('action', function($AppNotification) {
            $edit_link = '<a href="'.route('admin.app_notification.edit',$AppNotification->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i> </a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$AppNotification->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i> </a>';
            return $edit_link.$delete_link;
        })
        ->editColumn('content', function($AppNotification) {
            $active = \Str::limit(strip_tags($AppNotification->content),30);
            return $active;
        })
        ->editColumn('is_active', function($AppNotification) {
            $active = '-';
            if($AppNotification->is_active == 1){
                $active = '<input type="checkbox" class="bootstrap-switch" onchange="change_active(this,'.$AppNotification->id.')" checked data-size="small"/>';
            }
            else{
                $active = '<input type="checkbox" class="bootstrap-switch" onchange="change_active(this,'.$AppNotification->id.')" data-size="small"/>';
            }
            return ($active) ? $active : '-';
        })
        ->escapeColumns(['*'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        return view('Admin.AppNotification.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required|max:255',
            'notification_date' => 'required',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $AppNotification = AppNotification::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->is_active,
            'notification_date' => date('Y-m-d',strtotime($request->notification_date)),
        ]);
        Session::flash('success', 'App Notification Saved Successful.'); 
        return Redirect()->route('admin.app_notification.list');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AppNotification  $appNotification
     * @return \Illuminate\Http\Response
     */
    public function show(AppNotification $appNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AppNotification  $appNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(AppNotification $appNotification,$id)
    {
        $notification = AppNotification::where('id',$id)->first();
        return view('Admin.AppNotification.edit',compact('notification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AppNotification  $appNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required|max:255',
            'notification_date' => 'required',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $AppNotification = AppNotification::where('id',$id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->is_active,
            'notification_date' => date('Y-m-d',strtotime($request->notification_date)),
        ]);
        Session::flash('success', 'App Notification Update Successful.'); 
        return Redirect()->route('admin.app_notification.list');
    }
    public function delete(Request $request){
        $Faq = AppNotification::where('id',$request->id)->delete();
        return response()->json(['succsess'=>true]);
    }
    public function change_active_status(Request $request)
    {
        $Faq = AppNotification::where('id',$request->id)->update([
            'is_active' => $request->status,
        ]);
        return response()->json(array('succsess'=>true));
    }
}
