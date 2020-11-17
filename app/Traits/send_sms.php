<?php 
namespace App\Traits;

trait send_sms
{
	public function sms($phone,$message)
	{
	 	$postFields="type=smsquicksend&user=".config('services.rock2connect.user')."&pass=".config('services.rock2connect.pass')."&sender=".config('services.rock2connect.user')."&to_mobileno=".$phone."&sms_text=".$message;
	 	$postUrl='http://login.rock2connect.com/MOBILE_APPS_API/sms_api.php';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL,$postUrl );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		$response = curl_exec($ch);
		curl_close($ch);
		return true;
	}
}