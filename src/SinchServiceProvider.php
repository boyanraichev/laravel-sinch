<?php
namespace Boyo\Sinch;

use Illuminate\Support\ServiceProvider;

class SinchServiceProvider extends ServiceProvider
{
	
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
	        $this->commands([
	            \Boyo\Sinch\Commands\Test::class,
	        ]);
	    }
    }
    
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(\Boyo\Sinch\SinchSender::class, function () {
            return new \Boyo\Sinch\SinchSender();
        });
    }
    
}