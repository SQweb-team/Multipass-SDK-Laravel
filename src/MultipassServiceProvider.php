<?php
/*
 * Multipass Laravel SDK
 * @link https://github.com/SQweb-team/Multipass-SDK-Laravel
 * @license http://opensource.org/licenses/GPL-3.0
 */

namespace Multipass\LaravelSDK;

use Illuminate\Support\ServiceProvider;
use Multipass\LaravelSDK\MultipassController;

class MultipassServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config' => config_path()], 'multipass-config');
        view()->share('mltpss', new MultipassController);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/multipass.php', 'multipass-default');
        // $this->app->make('Multipass\LaravelSDK\MultipassController'); What is this for?
    }
}
