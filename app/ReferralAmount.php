<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferralAmount extends Model
{
	use SoftDeletes;
	protected $table = 'referral_amount';
    protected $fillable = [
        'id', 'amount','referral_amount_commission','created_at','updated_at','deleted_at'
    ];
}
