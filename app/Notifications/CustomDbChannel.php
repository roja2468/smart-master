<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomDbChannel
{
    public function send($notifiable, Notification $notification)
    {

        $data = $notification->toDatabase($notifiable);
        
        return $notifiable->routeNotificationFor('database')->create([
            'id' => $notification->id,
            //customize here
            'sender_id' => $data['sender_id'], //<-- comes from toDatabase() Method below
            'notification_type' => $data['notification_type'],
            'receiver_id' => $data['receiver_id'],
            'type' => get_class($notification),
            'data' => $data,
            'read_at' => null,
            'is_read' => '0',
        ]);
    }
}
