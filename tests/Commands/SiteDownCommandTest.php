<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests\Commands;

use Konsulting\Laravel\MaintenanceMode\Commands\SiteDownCommand;
use Konsulting\Laravel\MaintenanceMode\MaintenanceMode;
use Konsulting\Laravel\MaintenanceMode\MaintenanceModeProvider;

class SiteDownCommandTest extends CommandTestCase
{
    /** @test */
    public function it_activates_maintenance_mode()
    {
        $this->artisan('site:down', [
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
}
