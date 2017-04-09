<?php

namespace App\Providers\PR\Domain;

use Illuminate\Support\ServiceProvider;

class CommonDomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Contracts\Domain\ICommonDomainContract','App\Services\Domain\CommonDomainService');

    }
}