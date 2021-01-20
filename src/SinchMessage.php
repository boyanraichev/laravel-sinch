<?php

namespace Boyo\Sinch;

use Illuminate\Notifications\Notification;
use Boyo\Sinch\Exceptions\CouldNotSendMessage;
use Bulglish;

class SinchMessage
{
	/**
     * The phone number to send the message to
     *
     * @var string
     */
    public $to = '';
    
    /**
     * The delivery channel - default is sms
     *
     * @var string
     */
    public $channel = 'sms';
    
    /**
     * The delivery channels possible - 'sms' only allowed atm
     *
     * @var string
     */
    private $allowedChannels = ['sms'];
    
    /**
     * The message content for SMS.
     *
     * @var string
     */
    public $messageSMS = '';
    
    /**
     * The message unique id
     *
     * @var string
     */
    public $id = '';
    
    /**
     * The prefix - overwrites global setting
     *
     * @var string
     */
    public $prefix = false;
    
    /**
     * @param  string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
    }
    
    /**
     * Use this method to build the json request
     *
     *
     * @return $this
     */
    public function getMessage() {
	    
	    $this->bulglish = !empty(config('services.sinch.bulglish'));
	    
		$this->limitLength = empty(config('services.sinch.allow_multiple'));
		
		if ($this->prefix===false) {
			$this->prefix = config('services.sinch.prefix');
		}
	    	    
		if (empty($this->to)) { 
            throw CouldNotSendMessage::telNotProvided();				
		}
		
		$json = [
			'to' => [ $this->to ],
            'from' => config('services.sinch.from'),
		];

	    switch($this->channel) {
		    case 'sms':
				
				$sms_message_processed = $this->getSmsText();
				
		    	$json['body'] = $sms_message_processed;
		    	
				break;
	    }
	    
	    return $json;
	    
    }
    
    /**
     * Use this method to set custom content in SMS messages
     *
     *
     * @return $this
     */
    public function sms(string $text = '') {
	    
	    $this->messageSMS = $text;
	    
	    return $this;
	    
    }
    
    /**
     * Set the phone number of the recipient
     *
     * @param  string $to
     *
     * @return $this
     */
    public function to(string $to) {
	    
	    $this->to = $to;
	    
	    return $this;
	    
    }
    
    /**
     * Set the delivery channel 
     *
     * @param  string $channel
     *
     * @return $this
     */
    public function channel(string $channel) {
    	
    	if (!in_array($channel, $this->allowedChannels)) {
		    throw CouldNotSendMessage::unknownChannel();
	    }
	    
        $this->channel = $channel;
        
        return $this;
        
    }
    
    /**
     * Get the processed SMS text
     *
     *
     * @return string $text
     */
    public function getSmsText() {
	    	    		    
    	if (empty($this->messageSMS)) { 
		    throw CouldNotSendMessage::contentNotProvided();				
		}
	    
	    $sms_message_processed = ( $this->prefix ?: '' ) . $this->messageSMS;
		    	
    	if ($this->bulglish) {
			$sms_message_processed = Bulglish::toLatin( $sms_message_processed );
		}
		
		if ($this->limitLength) {
			$sms_message_processed = $this->cutText( $sms_message_processed );
		}

		return $sms_message_processed;
		
    }
    
    /**
     * Cut text to limit of 160 characters 
     *
     * @param  string $text
     *
     * @return $text
     */
	private function cutText($text) {
	        
		if (mb_strlen($text) > 160) {
		
			$text = mb_substr($text, 0, 156);
			$text .= '...';    
			
		}
		
        return $text;
	}
}