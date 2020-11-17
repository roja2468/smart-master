<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens,Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'middle_name','email','password','address','city','state','gender','phone','remember_token','created_at','updated_at','deleted_at','is_active','is_block','otp','is_new_register','education','f_name','dob','is_verify','is_paid','profile_pic','referral_code','referral_user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function AuthAccessToken()
    {
        return $this->hasMany('App\AuthAccessToken','user_id','id')->where('revoked',0)->where('device_token','!=','')->where('device_type','!=','');
    }
    public function getProfilePicAttribute($value)
    {
        return ($value) ? asset('uploads/profile_pic').'/'.$value : asset('no-photo.png');
    }
}
