<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawAmount extends Model
{
	use SoftDeletes;
	protected $table = 'withdraw_amount';
    protected $fillable = [
        'id', 'user_id','gpay_mobile_no','amount','is_approve','created_at','updated_at','deleted_at'
    ];
    public function User()
	{
	   return $this->hasOne('App\User', 'id', 'user_id');
	}
}
