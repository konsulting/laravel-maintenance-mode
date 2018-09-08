<?php

namespace Konsulting\Laravel\MaintenanceMode;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Konsulting\Laravel\MaintenanceMode\Commands\SiteDownCommand;
use Konsulting\Laravel\MaintenanceMode\Commands\SiteUpCommand;
use Konsulting\Laravel\MaintenanceMode\Drivers\DriverInterface;

class MaintenanceModeProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/maintenance_mode.php' => config_path('maintenance_mode.php'),
        ]);

        $this->app->singleton(MaintenanceMode::class, function () {
            return new MaintenanceMode($this->getDriver());
        });

        if (config('maintenance_mode.override_illuminate_commands')) {
            $this->overrideIlluminateCommands();
        } else {
            $this->registerCommands();
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/maintenance_mode.php', 'maintenance_mode');
    }

    /**
     * Build the maintenance mode driver from config items.
     *
     * @return DriverInterface
     */
    protected function getDriver()
    {
        $driverKey = config('maintenance_mode.driver');
        $driverClass = config('maintenance_mode.aliases')[$driverKey];
        $config = config('maintenance_mode.driver_config')[$driverKey];

        return $this->app->make($driverClass)->setConfig($config);
    }

    /**
     * Override the default artisan up/down commands in the container.
     *
     * @return void
     */
    protected function overrideIlluminateCommands()
    {
        $this->app->extend('command.up', function ($upCommand, Application $app) {
            return $app->make(SiteUpCommand::class);
        });

        $this->app->extend('command.down', function ($downCommand, Application $app) {
            return $app->make(SiteDownCommand::class);
        });
    }

    /**
     * Register the site:up and site:down commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            SiteDownCommand::class,
            SiteUpCommand::class,
        ]);
    }
}
