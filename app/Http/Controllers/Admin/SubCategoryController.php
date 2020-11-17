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

class SubCategoryController extends Controller
{
    public function list(Request $request)
    {
        return view('Admin.Subcategory.list');
    }
    public function datatable(Request $request)
    {
        $Category = Category::where('parent_id', '!=', 0)->where('sub_parent_id', 0)->get();
        return Datatables::of($Category)
        ->addColumn('action', function($Category) {
            $edit_link = '<a href="'.route('admin.sub_categories.edit',$Category->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i> </a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$Category->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i> </a>';
            return $edit_link.$delete_link;
        })
        ->addColumn('parent_id', function($Category) {
            return ($Category->parent) ? $Category->parent->title : '-';
        })
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function add()
    {
        $parentCategory = Category::where('parent_id',0)->get();
        return view('Admin.Subcategory.add', compact('parentCategory'));
    }
    public function unique(Request $request)
    {
        $category = Category::where('title',$request->title)->where('parent_id', '!=', 0)->where('sub_parent_id', 0)->whereNull('deleted_at')->count();
        if($category > 0)
        {
            return response()->json(['data'=>true]);
        }
        return response()->json(['data'=>false]);
    }
    public function sub_category(Request $request)
    {
        $html = '';
        $childCategory = Category::where('parent_id',$request->category_id)->get();
        if(!$childCategory->isEmpty())
        {
            $html .= '<div class="form-group m-b-40"><select class="select2 m-b-10 select2-multiple parent_category" title="Select Parent!" style="width: 100%" onchange="get_sub_category(this)" name="parent_category[]"><option value="">-select category-</option>';
            foreach ($childCategory as $key => $Category) {
                $html .= '<option value="'.$Category->id.'">'.$Category->title.'</option>';
            }
            $html .= '</select></div>';
        }
        return response()->json(['status'=>true,'html'=>$html]);
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'max:255',
                Rule::unique('category')->where(function ($query) {
                    return $query->where('parent_id', '!=', 0)->where('sub_parent_id', 0)->whereNull('deleted_at');
                })
            ],
            'parent_category' => 'required',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $parentCategoryId = $request->parent_category;
        $Category = Category::create([
            'parent_id' => $parentCategoryId,
            'title' => $request->title,
        ]);
        Session::flash('success', 'Sub Category Saved Successful.'); 
        return Redirect()->route('admin.sub_categories.list');
    }
    public function edit(Request $request,$id)
    {
        //$parentCategory = Category::with('childrenRecursive')->where('parent_id',0)->get();
        $parentCategory = Category::where('parent_id', 0)->get();
        $category = Category::where('id',$id)->first();
        return view('Admin.Subcategory.edit',compact('parentCategory','category'));
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'max:255',
                Rule::unique('category')->where(function ($query) use($id) {
                    return $query->where('parent_id', '!=', 0)->where('sub_parent_id', 0)->where('id', '!=', $id)->whereNull('deleted_at');
                })
            ],
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        
        $Category = Category::where('id',$id)->first();
        if($Category){
            $Category->parent_id = $request->parent_category;
            $Category->title = $request->title;
            $Category->save();
            Session::flash('success', 'Sub Category Updated Successful.'); 
            return Redirect()->route('admin.sub_categories.list');
        }else{
            Session::flash('error', 'Sub Category Update Unsuccessful.'); 
            return Redirect()->route('admin.sub_categories.list');
        }
       
        
    }
    public function delete(Request $request){
        $category = Category::where('id',$request->id)->first();
        /*$treeView = Category::GetChild($category->id);
        $deleteAll = Category::deleteCategory($treeView);*/
        if($category){
            Category::where('sub_parent_id', $request->id)->delete();
            $category->delete();
            return response()->json(['succsess'=>true]);
        }else{
            return response()->json(['succsess'=>false]);
        }
    }
    public function slider_image_delete(Request $request)
    {
        $ProductSlider = ProductSlider::where('id',$request->id)->first();
        if($ProductSlider)
        {
            if($ProductSlider->image)
            {
                $ProductSlider = ProductSlider::where('id',$request->id)->update(['is_delete'=>1]);
            }
        }
        return response()->json(['succsess'=>true]);
    }
}
