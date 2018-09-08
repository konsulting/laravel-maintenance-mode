<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests\Integration;

use Illuminate\Foundation\Console\DownCommand;
use Illuminate\Foundation\Console\UpCommand;

class ConfigTest extends IntegrationTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('maintenance_mode.override_illuminate_commands', false);
    }

    /** @test */
    public function it_does_not_override_the_default_illuminate_commands()
    {
        $this->assertInstanceOf(UpCommand::class, $this->app->make('command.up'));
        $this->assertInstanceOf(DownCommand::class, $this->app->make('command.down'));
    }
}
