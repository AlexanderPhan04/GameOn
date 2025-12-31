<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class SocialiteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Configure Socialite for development environment
        if (config('app.env') === 'local') {
            Socialite::extend('google', function ($app) {
                $config = $app['config']['services.google'];

                // Create Guzzle client with SSL verification disabled
                $httpClient = new Client([
                    'verify' => false,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ],
                ]);

                return Socialite::buildProvider(
                    \Laravel\Socialite\Two\GoogleProvider::class,
                    $config
                )->setHttpClient($httpClient);
            });
        }
    }

    public function register()
    {
        //
    }
}
