<?php

namespace App\Providers\PR\Domain;

use Illuminate\Support\ServiceProvider;

class UserDomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Contracts\Domain\IUserDomainContract','App\Services\Domain\UserDomainService');

    }
}