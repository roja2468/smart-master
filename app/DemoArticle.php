<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DemoArticle extends Model
{
	use SoftDeletes;
	protected $table = 'demo_article';
    protected $fillable = [
        'id','package_id','image','file','is_active','created_at','updated_at','deleted_at'
    ];
	public function getImageAttribute($value)
    {
        return ($value) ? asset('uploads/demo_article').'/'.$value : '';
    }
    public function getFileAttribute($value)
    {
        return ($value) ? asset('uploads/demo_article').'/'.$value : '';
    }
    public function Package()
    {
       return $this->hasOne("App\Package",'id','package_id');
    }
}
