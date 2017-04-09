<?php

namespace App\Providers\PR\Rest;

use Illuminate\Support\ServiceProvider;

class EmployeeRestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Rest\IEmployeeRestContract','App\Services\Rest\EmployeeRestService');
    }
}