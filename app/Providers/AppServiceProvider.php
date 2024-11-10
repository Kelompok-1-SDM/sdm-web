<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define a custom macro with token interceptor
        Http::macro('withAuthToken', function ()  {
            // Retrieve the token from cache
            $token = Cache::get('api_jwt_token');

            return Http::withHeaders([
                'Authorization' => $token ? "Bearer $token" : '',
            ]);
        });
    }
}
