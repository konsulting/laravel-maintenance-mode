<?php

namespace Konsulting\Laravel\MaintenanceMode;

use Carbon\Carbon;
use Konsulting\Laravel\MaintenanceMode\Drivers\DriverInterface as Driver;

class MaintenanceMode
{
    /**
     * The driver responsible for storing the maintenance mode indicator.
     *
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
     * @param int    $secondsToRetry
     * @return bool
     */
    public function on($message = '', $allowedIpAddresses = [], $secondsToRetry = null)
    {
        $payload = $this->getDownPayload($message, $allowedIpAddresses, $secondsToRetry);

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
     * @return DownPayload
     */
    public function getDownInformation()
    {
        return $this->driver->downPayload();
    }

    /**
     * Get the payload to be placed in the "down" file.
     *
     * @param string $message
     * @param array  $allowedIpAddresses
     * @param int    $secondsToRetry
     * @return DownPayload
     */
    protected function getDownPayload($message = '', $allowedIpAddresses = [], $secondsToRetry = null)
    {
        return new DownPayload([
            'time'    => Carbon::now()->getTimestamp(),
            'message' => $message,
            'retry'   => $secondsToRetry,
            'allowed' => $allowedIpAddresses,
        ]);
    }
}
