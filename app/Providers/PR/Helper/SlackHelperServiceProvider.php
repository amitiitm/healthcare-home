<?php

namespace App\Providers\PR\Helper;

use Illuminate\Support\ServiceProvider;

class SlackHelperServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Contracts\Helper\ISlackHelperContract','App\Services\Helper\SlackHelperService');

    }
}