<?php

namespace App\Providers\PR\Rest;

use Illuminate\Support\ServiceProvider;

class CommonRestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Rest\ICommonRestContract','App\Services\Rest\CommonRestService');
    }
}