<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests\Drivers;

use Konsulting\Laravel\MaintenanceMode\Drivers\DriverInterface;
use Konsulting\Laravel\MaintenanceMode\MaintenanceMode;
use Konsulting\Laravel\MaintenanceMode\Tests\TestCase;

class DriverTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $driverClass;

    /**
     * @var MaintenanceMode
     */
    protected $maintenanceMode;

    protected function setUp()
    {
        parent::setUp();

        $this->maintenanceMode = new MaintenanceMode(new $this->driverClass);
    }

}
