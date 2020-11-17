<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
	use SoftDeletes;
	protected $table = 'comment';
    protected $fillable = [
        'id', 'comment_for_id','comment','user_id','is_approve','type','created_at','updated_at','deleted_at'
    ];
    public function User()
	{
	   return $this->hasOne('App\User', 'id', 'user_id');
	}
    public function TopicVideo()
	{
	   return $this->hasOne('App\TopicVideo', 'id', 'comment_for_id');
	}
    public function TopicDocument()
	{
	   return $this->hasOne('App\TopicDocument', 'id', 'comment_for_id');
	}
}
