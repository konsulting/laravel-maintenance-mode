<?php

namespace Konsulting\Laravel\MaintenanceMode\Drivers;

abstract class BaseDriver
{
    /**
     * The driver configuration array.
     *
     * @var array
     */
    protected $config;

    /**
     * BaseDriver constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
