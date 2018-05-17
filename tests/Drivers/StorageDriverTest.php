<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests\Drivers;

use Konsulting\Laravel\MaintenanceMode\Drivers\StorageDriver;
use Storage;
use STS\Filesystem\VfsFilesystemServiceProvider;

class StorageDriverTest extends DriverTestCase
{
    protected $driverClass = StorageDriver::class;

    protected function driverConfig()
    {
        return ['file_path' => 'maintenance/mode/down'];
    }

    public function getPackageProviders($app)
    {
        return [VfsFilesystemServiceProvider::class];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.local', ['driver' => 'vfs']);
    }

    /** @test */
    public function it_takes_the_site_down()
    {
        $this->maintenanceMode->on();

        $this->assertTrue(Storage::exists('maintenance/mode/down'));
        $this->assertTrue($this->maintenanceMode->isOn());
    }

    /** @test */
    public function it_brings_the_site_back_up()
    {
        $this->makeDownFile();
        $this->maintenanceMode->off();

        $this->assertFalse(Storage::exists('maintenance/mode/down'));
        $this->assertFalse($this->maintenanceMode->isOn());
    }

    protected function makeDownFile()
    {
        Storage::put('maintenance/mode/down', '');

        $this->assertTrue($this->maintenanceMode->isOn());
    }
}
