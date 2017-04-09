<?php

namespace App\Providers\PR\Helper;

use Illuminate\Support\ServiceProvider;

class SMSHelperServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Contracts\Helper\ISMSHelperContract','App\Services\Helper\SMSHelperService');

    }
}