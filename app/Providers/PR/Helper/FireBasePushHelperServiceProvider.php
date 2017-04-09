<?php

namespace App\Providers\PR\Helper;

use Illuminate\Support\ServiceProvider;

class FireBasePushHelperServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Contracts\Helper\IFireBasePushHelperContract','App\Services\Helper\FireBasePushHelperService');

    }
}
