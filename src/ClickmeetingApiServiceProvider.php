<?php

namespace lukawar\ClickmeetingApi;

use Illuminate\Support\ServiceProvider;
use lukawar\ClickmeetingApi\Clients\ClickMeetingApiClient;
use lukawar\ClickmeetingApi\Contracts\HttpClientInterface;
use lukawar\ClickmeetingApi\Services\ClickMeetingApiService;

class ClickmeetingApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/clickmeetingapi.php', 'clickmeetingapi'
        );

        $this->app->singleton(HttpClientInterface::class, function ($app) {
            return new ClickMeetingApiClient(
                config('services.clickmeeting.api_key'),
                config('services.clickmeeting.api_url')
            );
        });

        $this->app->singleton(ClickMeetingApiService::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/clickmeetingapi.php' => config_path('clickmeetingapi.php'),
        ]);
    }
}
