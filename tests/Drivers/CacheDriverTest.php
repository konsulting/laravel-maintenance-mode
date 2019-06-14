<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests\Drivers;

use Cache;
use Konsulting\Laravel\MaintenanceMode\Drivers\CacheDriver;

class CacheDriverTest extends DriverTestCase
{
    protected $driverClass = CacheDriver::class;

    protected function driverConfig()
    {
        return [
            'key' => 'maintenance.down'
        ];
    }

    /** @test */
    public function it_takes_the_site_down()
    {
        $this->maintenanceMode->on();

        $this->assertTrue(Cache::has('maintenance.down'));
        $this->assertTrue($this->maintenanceMode->isOn());
    }

    /** @test */
    public function it_brings_the_site_back_up()
    {
        $this->setDownPayloadInCache();
        $this->maintenanceMode->off();

        $this->assertFalse(Cache::has('maintenance.down'));
        $this->assertFalse($this->maintenanceMode->isOn());
    }

    protected function setDownPayloadInCache()
    {
        Cache::forever('maintenance.down', true);

        $this->assertTrue($this->maintenanceMode->isOn());
    }
}
