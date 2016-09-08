<?php
/*
 * SQweb PHP SDK
 * @author Pierre Lavaux <pierre@sqweb.com>
 * @author Bastien Botella <bastien@sqweb.com>
 * @author Mathieu Darrigade <mathieu@sqweb.com>
 * @link https://github.com/SQweb-team/SQweb-SDK-Laravel
 * @license http://opensource.org/licenses/GPL-3.0
 */

namespace Sqweb\Laravel_sdk;

use Illuminate\Support\ServiceProvider;
use Sqweb\Laravel_sdk\SqwebController;

class SqwebServiceProvider extends ServiceProvider
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
        $this->publishes([__DIR__ . '/../config' => config_path()], 'sqweb.php');
        $sqweb = new SqwebController();
        view()->share('sqweb', $sqweb);
    }

     /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/sqweb.php', 'sqweb_default_config');
        $this->app->make('Sqweb\Laravel_sdk\SqwebController');
    }
}
