<?php

namespace Boyo\Sinch;

use Illuminate\Notifications\Notification;
use Boyo\Sinch\SinchSender;
use Boyo\Sinch\SinchMessage;

class SinchChannel
{
	
    protected $client;
    
    public function __construct()
    {

    }
    
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        
        $message = $notification->toSms($notifiable);
        
        if (!$message instanceof SinchMessage) {
	        throw new \Exception('No message provided');
	    }
	    
	    // run the build functions
	    $message->build();
	    
        $client = new SinchSender();
        
        $client->send($message);
        
    }
    
    
}