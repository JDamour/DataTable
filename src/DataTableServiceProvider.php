<?php

namespace LaravelEnso\DataTable;

use Illuminate\Support\ServiceProvider;

class DataTableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/resources/assets/js/components' => resource_path('assets/js/vendor/laravel-enso/components'),
        ], 'datatable-component');

        $this->publishes([
            __DIR__.'/resources/assets/js/modules' => resource_path('assets/js/vendor/laravel-enso/modules'),
        ], 'datatable-options');

        $this->publishes([
            __DIR__.'/resources/DataTable' => app_path('DataTable'),
        ], 'datatable-class');

        $this->publishes([
            __DIR__.'/resources/assets/js/components' => resource_path('assets/js/vendor/laravel-enso/components'),
        ], 'update');
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
}
