<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\APIAIClient;
use GuzzleHttp\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
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
