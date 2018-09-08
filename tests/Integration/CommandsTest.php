<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests\Integration;

use Konsulting\Laravel\MaintenanceMode\Commands\SiteDownCommand;
use Konsulting\Laravel\MaintenanceMode\Commands\SiteUpCommand;

class CommandsTest extends IntegrationTestCase
{
    /** @test */
    public function it_activates_maintenance_mode()
    {
        $this->artisan('down', [
            '--message' => 'Test',
            '--retry'   => 123,
            '--allow'   => ['1.1.1.1', '2.2.2.2'],
        ]);

        $this->assertTrue($this->maintenanceMode->isOn());
        $data = $this->maintenanceMode->getDownInformation();
        $this->assertSame('Test', $data->getMessage());
        $this->assertSame(123, $data->getRetryTime());
        $this->assertSame(['1.1.1.1', '2.2.2.2'], $data->getAllowedAddresses());
    }

    /** @test */
    public function it_deactivates_maintenance_mode()
    {
        $this->maintenanceMode->on();
        $this->assertTrue($this->maintenanceMode->isOn());

        $this->artisan('up');
        $this->assertFalse($this->maintenanceMode->isOn());
    }

    /** @test */
    public function it_overrides_the_default_illuminate_commands_by_default()
    {
        $this->assertInstanceOf(SiteUpCommand::class, $this->app->make('command.up'));
        $this->assertInstanceOf(SiteDownCommand::class, $this->app->make('command.down'));

        $this->artisan('down');
        $this->assertTrue($this->maintenanceMode->isOn());

        $this->artisan('up');
        $this->assertFalse($this->maintenanceMode->isOn());
    }
}
