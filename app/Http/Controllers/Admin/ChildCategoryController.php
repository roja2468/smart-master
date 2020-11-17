<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Category;
use DataTables;
use Validator;
use Session;
use Auth;
use Flash;
use Redirect;

class ChildCategoryController extends Controller
{
    public function list(Request $request)
    {
        return view('Admin.Childcategory.list');
    }
    public function datatable(Request $request)
    {
        $Category = Category::where('parent_id', '!=', 0)->where('sub_parent_id', '!=', 0)->get();
        return Datatables::of($Category)
        ->addColumn('action', function($Category) {
            $edit_link = '<a href="'.route('admin.child_categories.edit',$Category->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i> </a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$Category->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i> </a>';
            return $edit_link.$delete_link;
        })
        ->addColumn('sub_parent_id', function($Category) {
            return ($Category->subparent) ? $Category->subparent->title : '-';
        })
         ->addColumn('parent_id', function($Category) {
            return ($Category->parent) ? $Category->parent->title : '-';
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function unique(Request $request)
    {
        $category = Category::where('title',$request->title)->where('parent_id', '!=', 0)->where('sub_parent_id', '!=', 0)->whereNull('deleted_at')->count();
        if($category > 0)
        {
            return response()->json(['data'=>true]);
        }
        return response()->json(['data'=>false]);
    }
    public function add()
    {
        $parentCategory = Category::where('parent_id',0)->get();
        $subCategory = Category::where('parent_id', '!=', 0)->get();
        return view('Admin.Childcategory.add', compact('parentCategory', 'subCategory'));
    }
    
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'max:255',
                Rule::unique('category')->where(function ($query) {
                    return $query->where('parent_id', '!=', 0)->where('sub_parent_id', '!=', 0)->whereNull('deleted_at');
                })
            ],
            'parent_category' => 'required',
            'sub_category' => 'required',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        
        $parentCategoryId = $request->parent_category;
        $subCategoryId = $request->sub_category;
        $Category = Category::create([
            'parent_id' => $parentCategoryId,
            'sub_parent_id' => $subCategoryId,
            'title' => $request->title,
        ]);
        Session::flash('success', 'Sub Category Saved Successful.'); 
        return Redirect()->route('admin.child_categories.list');
    }
    public function edit(Request $request,$id)
    {
        //$parentCategory = Category::with('childrenRecursive')->where('parent_id',0)->get();
        $category = Category::where('id',$id)->first();
        $parentCategory = Category::where('parent_id',0)->get();
        $subCategory = Category::where('parent_id', '!=', 0)->where('sub_parent_id', 0)->get();
        return view('Admin.Childcategory.edit',compact('parentCategory','category', 'subCategory'));
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'max:255',
                Rule::unique('category')->where(function ($query) use($id) {
                    return $query->where('parent_id', '!=', 0)->where('sub_parent_id', '!=', 0)->where('id', '!=', $id)->whereNull('deleted_at');
                })
            ],
            // 'title' => 'required|max:255|unique:category,title,'.$id,
            'parent_category' => 'required',
            'sub_category' => 'required',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        
        $Category = Category::where('id',$id)->first();
        if($Category){
            $Category->parent_id = $request->parent_category;
            $Category->sub_parent_id = $request->sub_category;
            $Category->title = $request->title;
            $Category->save();
            Session::flash('success', 'Child Category Updated Successful.'); 
            return Redirect()->route('admin.child_categories.list');
        }else{
            Session::flash('error', 'Child Category Update Unsuccessful.'); 
            return Redirect()->route('admin.child_categories.list');
        }
       
        
    }
    public function delete(Request $request){
        $category = Category::where('id', $request->id)->first();
        /*$treeView = Category::GetChild($category->id);
        $deleteAll = Category::deleteCategory($treeView);*/
        if($category){
            $category->delete();
            return response()->json(['succsess'=>true]);
        }else{
            return response()->json(['succsess'=>false]);
        }
    }
    public function get_sub_category(Request $request){
        $sub_cate_list = Category::where('parent_id', $request->parentId)->where('sub_parent_id', 0)->get();
        $html = '';
        if(!$sub_cate_list->isEmpty())
        {
            foreach ($sub_cate_list as $key => $category) {
                $html .= '<option value="'.$category->id.'">'.$category->title.'</option>';
            }
        }
        return response()->json(array('status'=>true,'html'=>$html));
    }
    public function get_child_category(Request $request){
        $child_cate_list = Category::where('parent_id', $request->parentId)->where('sub_parent_id', $request->subParentId)->get();
        $html = '';
        if(!$child_cate_list->isEmpty())
        {
            foreach ($child_cate_list as $key => $category) {
                $html .= '<option value="'.$category->id.'">'.$category->title.'</option>';
            }
        }
        return response()->json(array('status'=>true,'html'=>$html));
    }
}
