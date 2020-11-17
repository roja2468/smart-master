<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViewCount extends Model
{
	use SoftDeletes;
	protected $table = 'view_count';
    protected $fillable = [
        'id', 'type','for_id','user_id','created_at','updated_at','deleted_at'
    ];
}
