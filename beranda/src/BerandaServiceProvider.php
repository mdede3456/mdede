<?php

namespace Beranda;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BerandaServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->bind('SangrevBeranda', function () {
            return new \Beranda\SangrevBeranda;
        });
    }

    public function boot()
    {
        // Load Views, Migrations and Routes
        $this->loadViewsFrom(__DIR__ . '/../views', 'Beranda');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutes();

        // Publishes
        $this->setPublishes();
    }

    /**
     * Publishing the files that the user may override.
     *
     * @return void
     */
    protected function setPublishes()
    {
        // Config
        $this->publishes([
            __DIR__ . '/../config/beranda.php' => config_path('beranda.php')
        ], 'beranda-config');

        // Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'beranda-migrations');

        // Controllers
        $this->publishes([
            __DIR__ . '/../src/Http/Controllers' => app_path('Http/Controllers/vendor/beranda')
        ], 'beranda-controllers');

        // Views
        $this->publishes([
            __DIR__ . '/../views' => resource_path('views/vendor/beranda')
        ], 'beranda-views');

        // Assets
        $this->publishes([
            // CSS
            __DIR__ . '/../assets/css' => public_path('css/beranda'),
            // JavaScript
            __DIR__ . '/../assets/js' => public_path('js/beranda'),
            // Images
            __DIR__ . '/../assets/imgs' => storage_path('app/public/' . config('beranda.user_avatar.folder')),
        ], 'beranda-assets');
    }
    /**
     * Group the routes and set up configurations to load them.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        Route::group($this->routesConfigurations(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    /**
     * Routes configurations.
     *
     * @return array
     */
    private function routesConfigurations()
    {
        return [
            'prefix' => config('beranda.path'),
            'namespace' =>  config('beranda.namespace'),
            'middleware' => ['web', config('beranda.middleware')],
        ];
    }
}
