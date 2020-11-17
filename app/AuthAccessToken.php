<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthAccessToken extends Model
{
	protected $table = 'oauth_access_tokens';
    protected $fillable = [
        'id','user_id','client_id','name','scopes','revoked','created_at','updated_at','expires_at','device_token','imei','device_name','os_version','device_type'
    ];
}
