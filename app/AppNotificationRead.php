<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppNotificationRead extends Model
{
	use SoftDeletes;
	protected $table = 'app_notification_read';
	protected $fillable = [
		'id', 'user_id','app_notification_id','is_read','created_at','updated_at','deleted_at'
	];
}
