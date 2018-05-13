<?php

namespace Konsulting\Laravel\MaintenanceMode;

use Illuminate\Support\ServiceProvider;

class MaintenanceModeProvider extends ServiceProvider
{
    public function boot()
    {
        
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/maintenance_mode.php', 'maintenance_mode');
    }
}
