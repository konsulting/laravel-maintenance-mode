<?php

namespace Konsulting\Laravel\MaintenanceMode;

use Carbon\Carbon;
use Konsulting\Laravel\MaintenanceMode\Drivers\DriverInterface as Driver;

class MaintenanceMode
{
    /**
     * @var Driver
     */
    protected $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Turn maintenance mode on.
     *
     * @return bool
     */
    public function on()
    {
        return $this->driver->activate($this->getDownPayload());
    }

    /**
     * Turn maintenance mode off.
     *
     * @return bool
     */
    public function off()
    {
        return $this->driver->deactivate();
    }

    /**
     * Check if maintenance mode is on.
     *
     * @return bool
     */
    public function isOn()
    {
        return $this->driver->isActive();
    }

    /**
     * Get the payload to be placed in the "down" file.
     *
     * @return array
     */
    protected function getDownPayload()
    {
        return [
            'time'  => Carbon::now()->getTimestamp(),
            //            'message' => $this->option('message'),
            'retry' => static::getRetryTime(),
            //            'allowed' => $this->option('allow'),
        ];
    }

    /**
     * Get the number of seconds the client should wait before retrying their request.
     *
     * @return int|null
     */
    protected static function getRetryTime()
    {
        $retry = config('maintenance_mode.retry_time');

        return is_numeric($retry) && $retry > 0 ? (int) $retry : null;
    }
}
