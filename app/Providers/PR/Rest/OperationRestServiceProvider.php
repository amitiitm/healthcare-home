<?php

namespace App\Providers\PR\Rest;

use Illuminate\Support\ServiceProvider;

class OperationRestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Rest\IOperationRestContract','App\Services\Rest\OperationRestService');
    }
}