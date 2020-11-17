<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
	use SoftDeletes;
	protected $table = 'transaction';
    protected $fillable = [
        'id', 'user_id','package_id','transaction_id','order_id','amount','expiry_date','tnx_response','created_at','updated_at','deleted_at','is_expire'
    ];
    public function User()
	{
	   return $this->hasOne('App\User', 'id', 'user_id');
	}
    public function Package()
	{
	   return $this->hasOne('App\Package', 'id', 'package_id');
	}
}
