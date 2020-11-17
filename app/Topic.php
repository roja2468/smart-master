<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
	use SoftDeletes;
	protected $table = 'topic';
    protected $fillable = [
        'id', 'category_id','sub_category_id','child_category_id','title','is_active','created_at','updated_at','deleted_at'
    ];
    public function Category()
	{
	   return $this->hasOne('App\Category', 'id', 'category_id');
	}
    public function subCategory()
	{
	   return $this->hasOne('App\Category', 'id', 'sub_category_id');
	}
    public function childCategory()
	{
	   return $this->hasOne('App\Category', 'id', 'child_category_id');
	}
    public function TopicDocument()
	{
	   return $this->hasMany('App\TopicDocument', 'topic_id', 'id');
	}
    public function TopicVideo()
	{
	   return $this->hasMany('App\TopicVideo', 'topic_id', 'id');
	}
}
