<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
	use SoftDeletes;
	protected $table = 'contact';
    protected $fillable = [
        'id', 'name','subject','message','email','created_at','updated_at','deleted_at'
    ];
}
