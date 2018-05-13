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
     * @param string $message
     * @param array  $allowedIpAddresses
     * @return bool
     */
    public function on($message = '', $allowedIpAddresses = [])
    {
        $payload = $this->getDownPayload($message, $allowedIpAddresses);

        return $this->driver->activate($payload);
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
     * If maintenance mode is on, get the information provided when it was activated.
     *
     *
     */
    public function getDownInformation()
    {

    }

    /**
     * Get the payload to be placed in the "down" file.
     *
     * @param string $message
     * @param array  $allowedIpAddresses
     * @return array
     */
    protected function getDownPayload($message = '', $allowedIpAddresses = [])
    {
        return [
            'time'    => Carbon::now()->getTimestamp(),
            'message' => $message,
            'retry'   => static::getRetryTime(),
            'allowed' => $allowedIpAddresses,
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
