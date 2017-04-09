<?php

namespace App\Providers\PR\Rest;

use Illuminate\Support\ServiceProvider;

class NotificationRestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Rest\INotificationRestContract','App\Services\Rest\NotificationRestService');
    }
}