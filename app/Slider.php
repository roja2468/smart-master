<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
	use SoftDeletes;
	protected $table = 'slider';
    protected $fillable = [
        'id', 'image','is_active','created_at','updated_at','deleted_at'
    ];
	public function getImageAttribute($value)
    {
        return ($value) ? asset('uploads/slider').'/'.$value : '';
    }
}
