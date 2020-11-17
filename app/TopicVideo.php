<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopicVideo extends Model
{
	use SoftDeletes;
	protected $table = 'topic_video';
    protected $fillable = [
        'id', 'topic_id','title','video','image','is_active','created_at','updated_at','deleted_at','is_paid'
    ];
    public function Topic()
	{
	   return $this->hasOne('App\Topic', 'id', 'topic_id');
	}
	public function getImageAttribute($value)
    {
        return ($value) ? asset('uploads/topic_video').'/'.$value : '';
    }
}
