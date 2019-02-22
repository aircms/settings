<?php

namespace aircms\settings;

use aircms\settings\Classes\SettingsManager;
use aircms\settings\Contracts\Settings;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->publishes([__DIR__ . '/Config/settings.php' => config_path('settings.php'),], 'settings');

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/Translations', 'settings');

        $this->publishes([
            __DIR__ . '/Translations' => resource_path('lang/vendor/settings'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/Views', 'settings');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/settings.php', 'settings');

        $this->app->singleton(Settings::class, function () {
            return new SettingsManager();
        });
    }
}
