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

class TopicDocumentController extends Controller
{
    public function list(Request $request)
    {
        return view('Admin.TopicDocument.list');
    }
    public function datatable(Request $request)
    {
        $TopicDocument = TopicDocument::get();
        return Datatables::of($TopicDocument)
        ->editColumn('topic_id', function($TopicDocument) {
            return ($TopicDocument->Topic) ? $TopicDocument->Topic->title : '-';
        })
        ->editColumn('is_paid', function($TopicDocument) {
            return ($TopicDocument->is_paid == 1) ? 'Paid' : 'Free';
        })
        ->addColumn('image', function($TopicDocument) {
            $image = ' - ';
            if($TopicDocument->image!=''){
                $image = '<div class="image-product-div"><img src="'.$TopicDocument->image.'" onerror=this.src="'.asset('No_image_available.png').'" width="100px" class="image-product"></div>';
            }
            return $image;
        })
        ->addColumn('action', function($TopicDocument) {
            $edit_link = '<a href="'.route('admin.topic.document.edit',$TopicDocument->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i></a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$TopicDocument->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i></a>';
            return $edit_link.$delete_link;
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function add()
    {
        $Topic = Topic::all();
        return view('Admin.TopicDocument.add',compact('Topic'));
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|max:255',
            'title' => 'required|max:255',
            'is_paid' => 'required',
            'topic_document_image' => 'required|mimes:jpeg,jpg,gif,png',
            'document' => 'required|mimes:jpeg,jpg,gif,png,pdf',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $filename = '';
        if($request->hasFile('topic_document_image'))
        {
            $file = $request->file('topic_document_image');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/topic_document', $filename);
        }
        $documentFilename = '';
        if($request->hasFile('document'))
        {
            $file = $request->file('document');
            $documentFilename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/topic_document', $documentFilename);
        }
        $TopicDocument = TopicDocument::create([
            'topic_id' => $request->topic,
            'document' => $documentFilename,
            'image' => $filename,
            'title' => $request->title,
            'is_paid' => $request->is_paid,
        ]);
        Session::flash('success', 'Topic Document Saved Successful.'); 
        return Redirect()->route('admin.topic.document.list');
    }
    public function edit(Request $request,$id)
    {
        $Topic = Topic::all();
        $TopicDocument = TopicDocument::where('id',$id)->first();
        return view('Admin.TopicDocument.edit',compact('Topic','TopicDocument'));
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|max:255',
            'title' => 'required|max:255',
            'is_paid' => 'required',
            'topic_document_image' => 'mimes:jpeg,jpg,gif,png',
            'document' => 'mimes:jpeg,jpg,gif,png,pdf',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $TopicDocument = TopicDocument::where('id',$id)->first();
        $filename = $TopicDocument->image;
        if($request->hasFile('topic_document_image'))
        {
            $file = $request->file('topic_document_image');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/topic_document', $filename);
            $TopicDocument = TopicDocument::where('id',$id)->update([
                'image' => $filename,
            ]);
        }
        $documentFilename = $TopicDocument->document;
        if($request->hasFile('document'))
        {
            $file = $request->file('document');
            $documentFilename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/topic_document', $documentFilename);
            $TopicDocument = TopicDocument::where('id',$id)->update([
                'document' => $documentFilename,
            ]);
        }
        $TopicDocument = TopicDocument::where('id',$id)->update([
            'topic_id' => $request->topic,
            'title' => $request->title,
            'is_paid' => $request->is_paid,
        ]);
        Session::flash('success', 'Topic Document Update Successful.'); 
        return Redirect()->route('admin.topic.document.list');
    }
    public function delete(Request $request){
        $TopicDocument = TopicDocument::where('id',$request->id)->delete();
        return response()->json(['succsess'=>true]);
    }
}
