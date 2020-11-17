<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use DataTables;
use Validator;
use Session;
use Auth;
use Flash;
use Redirect;

class CategoryController extends Controller
{
    public function list(Request $request)
    {
        return view('Admin.Category.list');
    }
    
    public function datatable(Request $request)
    {
        $Category = Category::where('parent_id', 0)->get();
        return Datatables::of($Category)
        ->addColumn('action', function($Category) {
            $edit_link = '<a href="'.route('admin.category.edit',$Category->id).'" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i> </a>';
            $delete_link = '<a href="javascript:void(0);" onclick="delete_confirmation(this,'.$Category->id.')" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-window-close text-danger"></i> </a>';
            return $edit_link.$delete_link;
        })
        ->addColumn('image', function($Category) {
            $image = ' - ';
            if($Category->image!=''){
                $image = '<div class="image-product-div"><img src="'.$Category->image.'" onerror=this.src="'.asset('No_image_available.png').'" width="100px" class="image-product"></div>';
            }
            return $image;
        })
        /*->addColumn('child', function($Category) {
            $treeView = Category::GetChild($Category->id);
            $html ='<div class="myadmin-dd dd nestable">';
            if(!empty($treeView)){
                $html .= Category::MakeHtml($treeView,0);
            }
            $html .='</div>';
            return $html;
        })*/
        ->escapeColumns(['*'])
        ->make(true);
    }
    public function add()
    {
        $parentCategory = Category::where('parent_id',0)->get();
        return view('Admin.Category.add',compact('parentCategory'));
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
    public function unique(Request $request)
    {
        $category = Category::where('title',$request->title)->whereNull('deleted_at')->count();
        if($category > 0)
        {
            return response()->json(['data'=>true]);
        }
        return response()->json(['data'=>false]);
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:category,title,NULL,id,deleted_at,NULL',
            'image' => 'required|mimes:jpeg,jpg,gif,png',
            'description' => 'required',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $filename = '';
        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/category', $filename);
        }
        //$parentCategoryId = $request->parent_category;
        $Category = Category::create([
            //'parent_id' => $parentCategoryId,
            'title' => $request->title,
            'image' => $filename,
            'description' => $request->description,
        ]);
        Session::flash('success', 'Category Saved Successful.'); 
        return Redirect()->route('admin.category.list');
    }
    public function edit(Request $request,$id)
    {
        $parentCategory = Category::with('childrenRecursive')->where('parent_id',0)->get();
        $category = Category::where('id',$id)->first();
        
        return view('Admin.Category.edit',compact('parentCategory','category'));
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:category,title,'.$id.',id,deleted_at,NULL',
            'image' => 'mimes:jpeg,jpg,gif,png',
            'description' => 'required',
        ]);
        if($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $filename = '';
        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $filename = time().'_'.trim($file->getClientOriginalName());
            $file->move(public_path().'/uploads/category', $filename);
        }
        $Category = Category::where('id',$id)->first();
        if($Category){
            $Category->description = $request->description;
            $Category->title = $request->title;
            if($request->hasFile('image')){
                $Category->image = $filename;  
            }
            $Category->save();
            Session::flash('success', 'Category Updated Successful.'); 
            return Redirect()->route('admin.category.list');
        }else{
            Session::flash('error', 'Category Update Unsuccessful.'); 
            return Redirect()->route('admin.category.list');
        }
        //$parentCategoryId = $request->parent_category;
        /*$Category = Category::where('id',$id)->update([
            'parent_id' => 0,
            'title' => $request->title,
            'image' => $filename,
            'description' => $request->description,
        ]);*/
        
    }
    public function delete(Request $request){
        $category = Category::where('id', $request->id)->first();
        /*$treeView = Category::GetChild($category->id);
        $deleteAll = Category::deleteCategory($treeView);*/
        if($category){
            $subList = Category::where('parent_id', $category->id)->pluck('id');
            if(count($subList)>0){
                Category::whereIn('sub_parent_id', $subList)->delete();
                Category::where('parent_id', $category->id)->delete();
            }
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
