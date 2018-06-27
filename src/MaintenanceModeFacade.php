<?php

namespace Konsulting\Laravel\MaintenanceMode;

use Illuminate\Support\Facades\Facade;

class MaintenanceModeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return MaintenanceMode::class;
    }
}
