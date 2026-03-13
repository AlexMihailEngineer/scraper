<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google\Service\YouTube;
use Google\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(YouTube::class, function ($app) {
            $client = new Client();
            $client->setDeveloperKey(config('services.youtube.key'));
            return new YouTube($client);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
