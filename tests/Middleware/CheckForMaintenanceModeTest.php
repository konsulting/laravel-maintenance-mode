<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests\Middleware;

use Carbon\Carbon;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Request;
use Konsulting\Laravel\MaintenanceMode\MaintenanceMode;
use Konsulting\Laravel\MaintenanceMode\MaintenanceModeProvider;
use Konsulting\Laravel\MaintenanceMode\Middleware\CheckForMaintenanceMode;
use Konsulting\Laravel\MaintenanceMode\Tests\TestCase;

class CheckForMaintenanceModeTest extends TestCase
{
    /** @var CheckForMaintenanceMode */
    protected $middleware;

    /** @var MaintenanceMode */
    protected $maintenanceMode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new CheckForMaintenanceMode;
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
    public function it_lets_the_request_through_if_not_activated()
    {
        $result = $this->middleware->handle(Request::create(''), $this->nextClosure());

        $this->assertTrue($result);
    }

    /** @test */
    public function it_throws_a_maintenance_mode_exception_if_activated()
    {
        $this->maintenanceMode->on('message', [], 123);
        $this->assertTrue($this->maintenanceMode->isOn());

        try {
            $this->middleware->handle(Request::create(''), $this->nextClosure());
        } catch (MaintenanceModeException $e) {
            $this->assertEquals(Carbon::now(), $e->wentDownAt);
            $this->assertSame(123, $e->retryAfter);
            $this->assertSame('message', $e->getMessage());

            return;
        }

        $this->fail('An exception must be thrown.');
    }

    /** @test */
    public function it_lets_allowed_ip_addresses_through()
    {
        $this->maintenanceMode->on('message', ['1.1.1.1']);
        $this->assertTrue($this->maintenanceMode->isOn());

        $request = Request::create('', 'GET', [], [], [], ['REMOTE_ADDR' => '1.1.1.1']);
        $result = $this->middleware->handle($request, $this->nextClosure());

        $this->assertTrue($result);
    }

    protected function nextClosure()
    {
        return function (Request $request) {
            return true;
        };
    }
}
