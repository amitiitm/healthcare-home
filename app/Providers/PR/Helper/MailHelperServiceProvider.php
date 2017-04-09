<?php

namespace App\Providers\PR\Helper;

use Illuminate\Support\ServiceProvider;

class MailHelperServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Contracts\Helper\IMailHelperContract','App\Services\Helper\MailHelperService');

    }
}