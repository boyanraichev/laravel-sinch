<?php
namespace Boyo\Sinch\Commands;

use Illuminate\Console\Command;
use Boyo\Sinch\SinchSender;
use Boyo\Sinch\SinchMessage;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinch:test {phone : Phone to send to} {--channel=sms} {--message=test}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test message';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    
	    try {
		    
		    $phone = $this->argument('phone');
		    
		    $channel = $this->option('channel');
		    
		    $content = $this->option('message');
            
		    $message = new SinchMessage(time());
		    $message->to($phone)->sms($content);
		    
		    if (!empty($channel)) {
			    $message->channel($channel);
		    }
		    
		    $client = new SinchSender();
		    
		    $client->forceSend($message);
		    
	        $this->info('Message send');
			
		} catch(\Exception $e) {
			
			$this->error($e->getMessage());
			
		}
    }
}