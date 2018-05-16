<?php

namespace Konsulting\Laravel\MaintenanceMode\Drivers;

use Illuminate\Support\Facades\Storage;
use Konsulting\Laravel\MaintenanceMode\DownPayload;

class StorageDriver implements DriverInterface
{
    /**
     * Get the path to the down file.
     *
     * @return string
     */
    public function downFilePath()
    {
        return 'maintenance/down';
    }

    /**
     * Activate maintenance mode.
     *
     * @param DownPayload $payload
     * @return bool
     */
    public function activate(DownPayload $payload)
    {
        return Storage::put($this->downFilePath(), $payload->toJson());
    }

    /**
     * Deactivate maintenance mode.
     *
     * @return bool
     */
    public function deactivate()
    {
        return Storage::delete($this->downFilePath());
    }

    /**
     * Check if maintenance mode is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return Storage::exists($this->downFilePath());
    }

    /**
     * Get the information specified when the site was taken down.
     *
     * @return DownPayload
     */
    public function downPayload()
    {
        $content = Storage::get($this->downFilePath());

        return DownPayload::fromJson($content);
    }
}
