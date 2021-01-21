# Sinch Notification channel

This package adds a notification channel for Sinch services. You can use it to send SMS messages. Other channels might be available in the future.

## Installation

Install through Composer.

## Config

Add the following to your services config file.

```php
'sinch' => [
	'api_key' => env('SINCH_API_KEY',''),
	'service_plan_id' => env('SINCH_PLAN_ID',''),
	'from' => env('SINCH_FROM',''),
	'prefix' => '',
	'log' => env('SINCH_LOG',true),
	'log_channel' => env('SINCH_LOG_CHANNEL','stack'),
	'send' => env('SINCH_SEND',false),
	'bulglish' => true,
	'allow_multiple' => false,
],
```

- *log* if the messages should be written to a log file
- *log_channel* the log channel to log messages to
- *send* if the messages should be sent (production/dev environment)
- *bulglish* if cyrillic text should be converted to latin letters for SMS messages (cyrillic messages are limited to 67 characters)
- *allow_multiple* if SMS messages above 160 characters should be allowed (billed as multiple messages)

## Send test

To send a test message use the following artisan command:

`php artisan sinch:test phone --message='content' --channel=sms`

## Direct usage

You can instantiate a `Boyo\Sinch\SinchMessage` object and send it immediately.

```php
use Boyo\Sinch\SinchMessage;
use Boyo\Sinch\SinchSender;

class MyClass
{
	public function myFunction()
	{
		$message = (new SinchMessage())->to('359888888888')->channel('sms')->sms('SMS text');
		
		$client = new SinchSender();
		$client->send($message);	
	}
}
```

## Usage with notifications

1. Create a message file that extends `Boyo\Sinch\SinchMessage`. It can take whatever data you need in the construct and should implement a `vuild()` method that defines the messages text content - a good practice would be to render a view file, so that your message content is in your views. You should only define the methods for the delivery channels that your are going to use. 

```php
use Boyo\Sinch\SinchMessage;

class MyMessage extends SinchMessage 
{
	public function __construct($data)
    {
        $this->id = $data->id; // your unique message id, add other parameters if needed
    }
    
	public function build() {
		// set your sms text 
		$this->sms('SMS text');
		
		return $this;
	}	
}
```

2. In your Notification class you can now include the Sinch channel in the `$via` array returned by the `via()` method.

```php
use Boyo\Sinch\SinchChannel;

via($notifiable) 
{
	
	// ...
	
	$via[] = SinchChannel::class;
	
	return $via 
	
}
```

Within the same Notification class you should also define a method `toSms()`:

```php
public function toSms($notifiable)
{
	return (new MyMessage($unique_id))->to($notifiable->phone)->channel('sms');
}
```

The channel method is where you define the delivery channel you wish to use. 

- **sms** delivery by sms only (this is the default value, if you omit the channel method)
- other Sinch channels might be available in the future

