<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;
	protected $table = 'category';
    protected $fillable = [
        'id', 'parent_id', 'sub_parent_id','title','image','description','is_active','created_at','updated_at','deleted_at'
    ];
    public function Topic()
    {
       return $this->hasMany("App\Topic",'category_id','id');
    }
    public function children()
	{
	   return $this->hasMany(Category::class, 'parent_id');
	}
	public function childrenRecursive()
	{
	   return $this->children()->with('childrenRecursive');
	}
	public function parent()
	{
	   return $this->belongsTo(Category::class,'parent_id','id');
	}
	public function subparent()
	{
	   return $this->belongsTo(Category::class,'sub_parent_id','id');
	}
	public function parentRecursive()
	{
	   return $this->parent()->with('parentRecursive');
	}
    public static function GetChild($id=0){
        $RootCat = Category::with('childrenRecursive')->where('id',$id)->first()->toArray();
        return $RootCat;
    }
    public static function MakeHtml($arr = array(),$id=1)
    {   
        if (empty($arr)) return '';
        $treeView = '<ol class="dd-list">';
        $treeView .= '';
        $treeView .= '<li class="dd-item" data-id="'.++$id.'"><div class="dd-handle dd-nodrag">'.$arr['title'].'<div class="float-right"><a href="'.(route('admin.category.edit',$arr['id'])).'"><i class="fas fa-edit"></i></a> <a href="javascript:void(0);" onclick="delete_confirmation(this,'.$arr['id'].')"><i class="fas fa-trash"></i></a></div></div>';
        if(!empty($arr['children_recursive'])){
            foreach ($arr['children_recursive'] as $key => $children) {
                $treeView .= Category::MakeHtml($children,++$id);
            }
        }
        $treeView .= '</li></ol>';
        return $treeView;
    }
    public static function deleteCategory($arr = array())
    {   
        if (empty($arr)) return 'deleted';
        if(!empty($arr['children_recursive'])){
			Category::where('id',$arr['id'])->delete();
            foreach ($arr['children_recursive'] as $key => $children) {
				Category::where('id',$children['id'])->delete();
				Category::deleteCategory($children);
            }
		}
		else{
			Category::where('id',$arr['id'])->delete();
		}
        return 'deleted';
	}
	// public static function getOptions($arr = array(),$count=0)
	// {
	// 	// dd($arr,$count);
    //     if (empty($arr)) return '';
    //     if(!empty($arr['children_recursive'])){
	// 		$temp_count = $count;
	// 		$treeView = "<option>".str_repeat("-",$count).$arr['title']."</option>";
    //         foreach ($arr['children_recursive'] as $key => $children) {
    //             $treeView .= Category::getOptions($children,++$count);
    //         }
	// 	}
	// 	else{
	// 		$treeView = "<option>".str_repeat("-",$count).$arr['title']."</option>";
	// 	}
    //     return (isset($treeView))?$treeView:'';
	// }
	public static function categoryTree($parent_id = 0, $sub_mark = '',$selected = null){
		// $query = \DB::query("SELECT * FROM categories WHERE parent_id = $parent_id ORDER BY name ASC");
		$query = \DB::table('category')->where('parent_id',$parent_id)->where('deleted_at',null)->get();
		// dd($query);
		
		if($query->count() > 0){
			foreach ($query as $key => $row) {
				$row = json_decode(json_encode($row),true);
				echo '<option value="'.$row['id'].'" '.(($selected==$row['id'])?'selected':'').'>'.$sub_mark.$row['title'].'</option>';
				Category::categoryTree($row['id'], $sub_mark.'-',$selected);
			}
		}
	}
	public function getImageAttribute($value)
    {
        return ($value) ? asset('uploads/category').'/'.$value : asset('no-photo.png');
    }
}
