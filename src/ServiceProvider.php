<?php
namespace Puz\MultiColumnAuth;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/multicolumnauth.php' => config_path('puz/multicolumnauth.php'),
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/multicolumnauth.php', 'puz.multicolumnauth'
        );
    }
}
