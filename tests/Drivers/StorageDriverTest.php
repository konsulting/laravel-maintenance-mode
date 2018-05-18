<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests\Drivers;

use Carbon\Carbon;
use Konsulting\Laravel\MaintenanceMode\DownPayload;
use Konsulting\Laravel\MaintenanceMode\Drivers\StorageDriver;
use Konsulting\Laravel\MaintenanceMode\MaintenanceMode;
use Storage;
use STS\Filesystem\VfsFilesystemServiceProvider;

class StorageDriverTest extends DriverTestCase
{
    protected $driverClass = StorageDriver::class;

    protected function driverConfig()
    {
        return [
            'file_path' => 'maintenance/mode/down',
            'disk'      => 'default',
        ];
    }

    public function getPackageProviders($app)
    {
        return [VfsFilesystemServiceProvider::class];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.local', ['driver' => 'vfs']);
        $app['config']->set('filesystems.disks.my_disk', ['driver' => 'vfs']);
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

    /** @test */
    public function it_uses_a_custom_disk()
    {
        $driver = new $this->driverClass([
            'file_path' => 'maintenance/mode/down',
            'disk'      => 'my_disk',
        ]);
        $maintenanceMode = new MaintenanceMode($driver);

        $maintenanceMode->on('testing');

        $this->assertTrue(Storage::disk('my_disk')->exists('maintenance/mode/down'));
        $this->assertTrue($maintenanceMode->isOn());

        $expectedPayload = new DownPayload(['time' => Carbon::now()->getTimestamp(), 'message' => 'testing']);
        $this->assertEquals($expectedPayload, $maintenanceMode->getDownInformation());

        $maintenanceMode->off();
        $this->assertFalse(Storage::disk('my_disk')->exists('maintenance/mode/down'));
        $this->assertFalse($maintenanceMode->isOn());
    }

    protected function makeDownFile()
    {
        Storage::put('maintenance/mode/down', '');

        $this->assertTrue($this->maintenanceMode->isOn());
    }
}
