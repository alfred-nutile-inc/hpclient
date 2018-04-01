<?php
namespace AlfredNutileInc\HPClient;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class HubPlannerProvider extends ServiceProvider
{

    public function boot()
    {

        $this->publishes([
            __DIR__ . '/../hpconfig.php' => config_path('hpconfig.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../hpconfig.php',
            'hpconfig'
        );

        $this->app->singleton(Client::class, function () {
            return new \GuzzleHttp\Client([
                'base_uri' => 'https://api.hubplanner.com/',
                'headers' => [
                    'Authorization' => config('hpconfig.key'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ]);
        });

        $this->app->singleton(
            HubPlannerClient::class,
            function () {
                $client = \App::make(Client::class);
                return new HubPlannerClient($client);
            }
        );
    }
}
