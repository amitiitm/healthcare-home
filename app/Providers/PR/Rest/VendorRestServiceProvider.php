<?php

namespace App\Providers\PR\Rest;

use Illuminate\Support\ServiceProvider;

class VendorRestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Rest\IVendorRestContract','App\Services\Rest\VendorRestService');
    }
}