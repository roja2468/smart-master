<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
	use SoftDeletes;
	protected $table = 'faq';
    protected $fillable = [
        'id', 'title','content','is_active','created_at','updated_at','deleted_at'
    ];
}
