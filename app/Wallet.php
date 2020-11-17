<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
	use SoftDeletes;
	protected $table = 'wallet';
    protected $fillable = [
        'id', 'user_id','amount','type','referral_user_id','is_referral','created_at','updated_at','deleted_at','gpay_mobile_no'
    ];
    public function User()
	{
	   return $this->hasOne('App\User', 'id', 'user_id');
	}
    public function ReferralUser()
	{
	   return $this->hasOne('App\User', 'id', 'referral_user_id');
	}
}
