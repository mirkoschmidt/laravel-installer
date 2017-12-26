<?php

namespace MirkoSchmidt\LaravelInstaller\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use MirkoSchmidt\LaravelInstaller\Middleware\canInstall;
use MirkoSchmidt\LaravelInstaller\Middleware\canUpdate;
use MirkoSchmidt\LaravelInstaller\Controllers\WelcomeController;

class LaravelInstallerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->publishFiles();
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    /**
     * Bootstrap the application events.
     *
     * @param $void
     */
    public function boot(Router $router)
    {
        $router->middlewareGroup('install',[CanInstall::class]);
        $router->middlewareGroup('update',[CanUpdate::class]);
        if (!$this->app->runningInConsole()) {
            $this->checkProjectStatus();
        }
    }

    /**
     * Publish config file for the installer.
     *
     * @return void
     */
    protected function publishFiles()
    {
        $this->publishes([
            __DIR__.'/../Config/installer.php' => base_path('config/installer.php'),
        ], 'laravelinstaller');

        $this->publishes([
            __DIR__.'/../assets' => public_path('installer'),
        ], 'laravelinstaller');

        $this->publishes([
            __DIR__.'/../Views' => base_path('resources/views/vendor/installer'),
        ], 'laravelinstaller');

        $this->publishes([
            __DIR__.'/../Lang' => base_path('resources/lang'),
        ], 'laravelinstaller');
    }

    /**
     * Generate .env and redirect to '/install' or redirect directly to '/install'
     *
     * @return void
     */
    protected function checkProjectStatus()
    {
        if (empty($this->app->make('config')->get('app.key')) && url()->current() === url('/')) {
            WelcomeController::start();
        } elseif ((empty($this->app->make('config')->get('database.connections.mysql.database'))
                || $this->app->make('config')->get('database.connections.mysql.database') === 'homestead')
            && !preg_match('/^' .preg_quote(url('/install'), '/g') . '/', url()->current())
        ) {
            redirect('/install')->send();
        }
    }
}
