<?php

namespace Konsulting\Laravel\MaintenanceMode\Drivers;

use Konsulting\Laravel\MaintenanceMode\DownPayload;

interface DriverInterface
{
    /**
     * Activate maintenance mode.
     *
     * @param DownPayload $payload
     * @return bool
     */
    public function activate(DownPayload $payload);

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

    /**
     * Get the information specified when the site was taken down.
     *
     * @return DownPayload
     */
    public function downPayload();
}
