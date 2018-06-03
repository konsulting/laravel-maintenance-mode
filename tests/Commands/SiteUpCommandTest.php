<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests;

use Konsulting\Laravel\MaintenanceMode\Commands\SiteDownCommand;
use Konsulting\Laravel\MaintenanceMode\MaintenanceMode;
use Konsulting\Laravel\MaintenanceMode\MaintenanceModeProvider;

class SiteUpCommandTest extends TestCase
{
    /** @var MaintenanceMode */
    protected $maintenanceMode;

    protected function setUp()
    {
        parent::setUp();

        $this->maintenanceMode = app(MaintenanceMode::class);
    }

    public function getPackageProviders($app)
    {
        return [MaintenanceModeProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('maintenance_mode.driver', 'cache');
    }

    /** @test */
    public function it_activates_maintenance_mode()
    {
        $this->maintenanceMode->on();
        $this->assertTrue($this->maintenanceMode->isOn());

        $this->artisan('site:up');
        $this->assertFalse($this->maintenanceMode->isOn());
    }
}
