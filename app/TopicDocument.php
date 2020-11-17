<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopicDocument extends Model
{
	use SoftDeletes;
	protected $table = 'topic_document';
    protected $fillable = [
        'id', 'topic_id','title','document','image','is_active','created_at','updated_at','deleted_at','is_paid'
    ];
    public function Topic()
	{
	   return $this->hasOne('App\Topic', 'id', 'topic_id');
	}
	public function getImageAttribute($value)
    {
        return ($value) ? asset('uploads/topic_document').'/'.$value : '';
    }
	public function getDocumentAttribute($value)
    {
        return ($value) ? asset('uploads/topic_document').'/'.$value : '';
    }
}
