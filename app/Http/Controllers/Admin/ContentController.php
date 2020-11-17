<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AboutUs;
use App\PrivacyPolicy;
use Session;
use Auth;
use Validator;
use Redirect;

class ContentController extends Controller
{
    public function list()
    {
        return view('Admin.Content.list');
    }
    public function about_list()
    {
    	$about_us = AboutUs::get();
        return view('Admin.Content.aboutList',compact('about_us'));
    }
    public function editAboutUs()
    {
    	$about_us = AboutUs::first();
        return view('Admin.Content.aboutEdit',compact('about_us'));
    }
    public function updateAboutUs(Request $request,$id)
    {
    	$validator = Validator::make($request->all(), [
    	    'title' => 'required',
            'content' => 'required',
    	]);
    	if($validator->fails()) {
    	    return Redirect::back()->withInput()->withErrors($validator);
    	}
    	AboutUs::where('id',$id)->update([
    		'title'=>$request->title,
            'content'=>$request->content,
    	]);
    	Session::flash('success', 'About Us Updated Successful.'); 
    	return Redirect()->route('admin.about_us.list');
    }
    public function privacyPolicyList()
    {
        $PrivacyPolicy = PrivacyPolicy::get();
        return view('Admin.Content.privacyPolicyList',compact('PrivacyPolicy'));
    }
    public function editPrivacyPolicy()
    {
        $PrivacyPolicy = PrivacyPolicy::first();
        return view('Admin.Content.privacyPolicyEdit',compact('PrivacyPolicy'));
    }
    public function updatePrivacyPolicy(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        PrivacyPolicy::where('id',$id)->update([
            'title'=>$request->title,
            'content'=>$request->content,
        ]);
        Session::flash('success', 'Privacy Policy Updated Successful.'); 
        return Redirect()->route('admin.privacy_policy.list');
    }
}
