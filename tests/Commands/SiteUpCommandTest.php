<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests\Commands;

use Konsulting\Laravel\MaintenanceMode\MaintenanceMode;
use Konsulting\Laravel\MaintenanceMode\MaintenanceModeProvider;

class SiteUpCommandTest extends CommandTestCase
{
    /** @test */
    public function it_activates_maintenance_mode()
    {
        $this->maintenanceMode->on();
        $this->assertTrue($this->maintenanceMode->isOn());

        $this->artisan('site:up');
        $this->assertFalse($this->maintenanceMode->isOn());
    }
}
