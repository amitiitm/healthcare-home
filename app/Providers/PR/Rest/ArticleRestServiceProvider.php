<?php

namespace App\Providers\PR\Rest;

use Illuminate\Support\ServiceProvider;

class ArticleRestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Rest\IArticleRestContract','App\Services\Rest\ArticleRestService');
    }
}