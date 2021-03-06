<?php

namespace Konsulting\Laravel\MaintenanceMode\Drivers;

use Illuminate\Support\Arr;

abstract class BaseDriver
{
    /**
     * The driver configuration array.
     *
     * @var array
     */
    protected $config;

    /**
     * Set the driver config.
     *
     * @param array $config
     * @return BaseDriver
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get an item from config.
     *
     * @param string $key
     * @return mixed
     */
    protected function config($key)
    {
        return Arr::get($this->config, $key);
    }
}
