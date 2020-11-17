<?php 
namespace App\Traits;

trait push_notification
{
	public function notification_push($token,$data,$device_type='')
	{
		// dd($device_type);
		$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
		
		if($device_type == '1')
		{
			$fcmNotification = array(
			  	"to"        => $token, //single token
			  	"priority" => "high",
			  	"data" => $data,
			);
		}
		else if($device_type == '2')
		{
			$notification = array(
			  	"title" => $data['title'],
			  	"sound" => 'Default',
			  	"badge" => $data['badge'],
			  	"content_available"=> true,
			);

			$data_new = array(
				"badge" => $data['badge'],
				"data" =>$data
			);

			//$extraNotificationData = ["message" => $notification,"data" =>$data_new];

			$fcmNotification = array(
			  	"to"        => $token, //single token
			  	"priority" => "high",
			  	"notification" => $notification,
			  	"data" => $data['extra_data'],
			);
		}
		$headers = array(
		  	'Content-Type: application/json',
		  	'Authorization: key=AAAA1-QVqLM:APA91bE7XclnV2opW_vBCZHn-udQiImyB-XIdmByVjNdB-OcpWfOoxNJ1Q4YR3b0RhxDSUCYPO_wOLPKHDA-_lydzvzhg0gneqLMk2qeV-lJdmDrTMytdUK7DXulxV19uUx4ADDczCnt',
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$fcmUrl);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
		$result = curl_exec($ch);
		curl_close($ch);
		return true;
	}
}