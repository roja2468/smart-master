<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
	use SoftDeletes;
	protected $table = 'package';
    protected $fillable = [
        'id', 'title','amount','start_date_time','end_date_time','is_active','created_at','updated_at','deleted_at','main_category_id','package_mrp','package_offer','about_course_description','author_name','author_designation','author_qualification','author_profile_pic','time_frame'
    ];
	public function Category()
	{
	   return $this->hasOne("App\Category",'id','main_category_id');
	}
	public function Topic()
	{
	   return $this->hasMany("App\Topic",'category_id','main_category_id');
	}
	public function DemoArticle()
	{
	   return $this->hasMany("App\DemoArticle",'package_id','id');
	}
	public function DemoVideo()
	{
	   return $this->hasMany("App\DemoVideo",'package_id','id');
	}
	public function getAuthorProfilePicAttribute($value)
    {
        return ($value) ? asset('uploads/package_author_profile').'/'.$value : '';
    }
}
