<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests\Drivers;

use Illuminate\Support\Carbon;
use Konsulting\Laravel\MaintenanceMode\DownPayload;
use Konsulting\Laravel\MaintenanceMode\MaintenanceMode;
use Konsulting\Laravel\MaintenanceMode\Tests\TestCase;

abstract class DriverTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $driverClass;

    /**
     * @var MaintenanceMode
     */
    protected $maintenanceMode;

    /**
     * The config to pass to the driver.
     *
     * @var array
     */
    abstract protected function driverConfig();

    protected function setUp(): void
    {
        parent::setUp();

        $driver = app()->make($this->driverClass)
            ->setConfig($this->driverConfig());
        $this->maintenanceMode = new MaintenanceMode($driver);
    }

    /** @test */
    public function it_writes_a_payload_to_the_down_file()
    {
        $this->maintenanceMode->on('My message', ['127.0.0.1'], 120);

        $expected = new DownPayload([
            'time'    => Carbon::now()->getTimestamp(),
            'message' => 'My message',
            'retry'   => 120,
            'allowed' => ['127.0.0.1'],
        ]);

        $this->assertEquals($expected, $this->maintenanceMode->getDownInformation());
    }

}
