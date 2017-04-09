<?php

namespace App\Providers\PR\Domain;

use Illuminate\Support\ServiceProvider;

class VendorDomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Contracts\Domain\IVendorDomainContract','App\Services\Domain\VendorDomainService');

    }
}