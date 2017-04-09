<?php

namespace App\Providers\PR\Domain;

use Illuminate\Support\ServiceProvider;

class EmployeeDomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Contracts\Domain\IEmployeeDomainContract','App\Services\Domain\EmployeeDomainService');

    }
}