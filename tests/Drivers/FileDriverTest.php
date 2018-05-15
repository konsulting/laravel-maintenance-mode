<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests\Drivers;

use Konsulting\Laravel\MaintenanceMode\Drivers\FileDriver;
use Konsulting\Laravel\MaintenanceMode\Tests\RefreshStorageDirectory;

class FileDriverTest extends DriverTestCase
{
    use RefreshStorageDirectory;

    protected $driverClass = FileDriver::class;

    /** @test */
    public function it_takes_the_site_down()
    {
        $this->maintenanceMode->on();

        $this->assertFileExists(storage_path('maintenance/down'));
        $this->assertTrue($this->maintenanceMode->isOn());
    }

    /** @test */
    public function it_brings_the_site_back_up()
    {
        $this->makeDownFile();
        $this->maintenanceMode->off();

        $this->assertFileNotExists(storage_path('maintenance/down'));
        $this->assertFalse($this->maintenanceMode->isOn());
    }

    protected function makeDownFile()
    {
        if (! is_dir(storage_path('maintenance'))) {
            mkdir(storage_path('maintenance'));
        }

        touch(storage_path('maintenance/down'));
        $this->assertTrue($this->maintenanceMode->isOn());
    }
}
