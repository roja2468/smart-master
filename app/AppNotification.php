<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppNotification extends Model
{
	use SoftDeletes;
	protected $table = 'app_notifications';
	protected $fillable = [
		'id', 'title','content','is_active','notification_date','created_at','updated_at','deleted_at'
	];
}
