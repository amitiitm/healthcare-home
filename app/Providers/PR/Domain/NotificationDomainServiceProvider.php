<?php

namespace App\Providers\PR\Domain;

use Illuminate\Support\ServiceProvider;

class NotificationDomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Contracts\Domain\INotificationDomainContract','App\Services\Domain\NotificationDomainService');

    }
}