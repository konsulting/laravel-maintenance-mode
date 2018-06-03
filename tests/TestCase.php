<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests;


use Illuminate\Support\Carbon;
use Konsulting\Laravel\MaintenanceMode\MaintenanceModeProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp()
    {
        parent::setUp();

        Carbon::setTestNow('2000-01-01 12:00:00');
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[RefreshStorageDirectory::class])) {
            $this->refreshStorageDirectory();
        }

        return parent::setUpTraits();
    }
}
