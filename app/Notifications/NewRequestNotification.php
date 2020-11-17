<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\CustomDbChannel;
use App\User;
use Illuminate\Support\Facades\URL;

class NewRequestNotification extends Notification
{
    use Queueable;
    public $details;
    public $module_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details,$module_id)
    {
       $this->details = $details;
       $this->module_id = $module_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return ['database'];
        return [CustomDbChannel::class];

    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // $url = url('/admin/customer-order/detail/'.$this->module_id);
        
        // return (new MailMessage)
        //             ->line('New Customer Request.')
        //             ->action('View Request',$url)
        //             ->line('Request For'.' '.$this->details['for']);
        // return (new MailMessage)->view('emails.newcustomerrequest',['url' => $url,'detail' => 'New Order Customer']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            // 'data'     => $this->notifyData['data']
            // 'notification_type' => $this->details['notification_type'],
            // 'title' => $this->details['title'],
            // 'module_id'=> $this->details['module_id'],
            // 'for' => $this->details['for'],
            // 'created_by' => $this->details['created_by'],
            // 'sender_id' => $this->details['sender_id'],
            // 'receiver_id' => $this->details['receiver_id']
        ];
    }
    public function toDatabase($notifiable)
    {
        return [
            // 'messsage' => $this->details['title'],
            'notification_type' => $this->details['notification_type'],
            'title' => $this->details['title'],
            'module_id'=> $this->details['module_id'],
            'for' => $this->details['for'],
            'created_by' => $this->details['created_by'],
            'sender_id' => $this->details['sender_id'],
            'receiver_id' => $this->details['receiver_id']
        ];
    }
}
