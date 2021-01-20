<?php

namespace Boyo\Sinch;

use Boyo\Sinch\Exceptions\CouldNotSendMessage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SinchSender
{
	private $log = true;
	
	private $log_channel = 'stack';
	
	private $send = false;
	
	private $url = 'https://sms.api.sinch.com/xms/v1/';	
	
	private $headers = [
		'Accept' => 'application/json',
		'Content-Type' => 'application/json',
	];
	
	private $key = '';
	
	private $service_plan_id = '';
	
	// construct
	public function __construct() {
		
		// settings
		$this->key = config('services.sinch.api_key');
		$this->service_plan_id = config('services.sinch.service_plan_id');
		
		$this->url = "https://sms.api.sinch.com/xms/v1/{$this->service_plan_id}/batches";
		
		$this->headers['Authorization'] = "Bearer {$this->key}";
		
		$this->log = config('services.sinch.log');
		$this->send = config('services.sinch.send');
		$this->log_channel = config('services.sinch.log_channel');
				
		// setup Guzzle client
		$this->client = new Client([
			'base_uri' => $this->url,
			'headers' => $this->headers,
		]);
		
	}
	
	public function forceSend(SinchMessage $message) {
		
		$this->send = true;
		
		$this->send($message);
		
		return $this;
		
	}
	
	// send email
	public function send(SinchMessage $message) {
		
		try {
			
			$request = $message->getMessage();
							
			if($this->log) {
				Log::channel($this->log_channel)->info('Sinch message',$request);
			}
			
			if($this->send) {
			
				$response = $this->client->request('POST', '', [ 'json' => $request ]);
				
				$result = (string) $response->getBody();
				
				if($this->log) {
					Log::channel($this->log_channel)->info('Sinch response: '.$result);
				}
				
/*
	            if (strpos($result, 'SEND_OK') === false) {
	                throw new \Exception($result);
	            }
*/
		
			}
			
		} catch(\Exception $e) {
			
			Log::channel($this->log_channel)->info('Could not send Sinch message ('.$e->getMessage().')');
			
		}
		
	}
	
	
}
