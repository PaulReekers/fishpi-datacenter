<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\APIAIClient;
use GuzzleHttp\Client;
use App\Command;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Command::created(function ($command){
            try {
                $name = uniqid("fishpi-"); // just generate a unquie name everytime
                $client = new \App\Extensions\phpMQTT(env('MQTT_HOST'), env('MQTT_PORT', 1883), $name);
                if($client->connect()) {
                    $client->publish('fishpi/' . $command->command, $command->data);
                    $client->close();
                }
            } catch (\Exception $e) {
                throw new \Exception("Publishing to fishpi failed", $e);
            }
        }); 
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(APIAIClient::class, function ($app) {
            return new APIAIClient(
                new Client([
                    'base_uri' => env('API_AI_URL')
                ]),
                env('API_AI_KEY')
            );
        });
    }
}
