<?php namespace App\Providers\View;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        // composing admin navigation
        $this->composeUserView();

        // composing customer navigation
        //$this->composeCustomerNavigation();

		// composing vendor navigation
        //$this->composeVendorNavigation();
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

    // composing admin navigation
    private function composeUserView()
    {
        view()->composer('layouts.front._navbar', 'App\Http\Composers\NavigationComposer@composeUserView');
        view()->composer('layouts.admin._navbar', 'App\Http\Composers\NavigationComposer@composeUserView');
        view()->composer('layouts.admin._sidemenu', 'App\Http\Composers\NavigationComposer@composeUserView');
    }

    // composing customer navigation
    private function composeCustomerNavigation()
    {
        view()->composer('layouts.dynamic.customer._navbar', 'OliveRoof\Http\Composers\NavigationComposer@composeCustomerNavigation');
    }

    // composing vendor navigation
    private function composeVendorNavigation()
    {
        view()->composer('layouts.dynamic.vendor._navbar', 'OliveRoof\Http\Composers\NavigationComposer@composeVendorNavigation');
    }

}
