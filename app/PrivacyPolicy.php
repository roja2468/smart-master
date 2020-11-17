<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrivacyPolicy extends Model
{
	use SoftDeletes;
	protected $table = 'privacy_policy';
    protected $fillable = [
        'id','title','content','created_at','updated_at','deleted_at'
    ];
}
