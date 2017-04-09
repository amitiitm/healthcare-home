<?php

namespace App\Providers\PR\Domain;

use Illuminate\Support\ServiceProvider;

class OperationDomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Contracts\Domain\IOperationDomainContract','App\Services\Domain\OperationDomainService');

    }
}