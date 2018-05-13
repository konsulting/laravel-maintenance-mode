<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 13/05/2018
 * Time: 22:32
 */

namespace Konsulting\Laravel\MaintenanceMode\Drivers;

interface DriverInterface
{
    /**
     * Activate maintenance mode.
     *
     * @param array $payload
     * @return bool
     */
    public function activate($payload);

    /**
     * Deactivate maintenance mode.
     *
     * @return bool
     */
    public function deactivate();

    /**
     * Check if maintenance mode is active.
     *
     * @return bool
     */
    public function isActive();
}
