<?php

namespace Konsulting\Laravel\MaintenanceMode\Drivers;

use Konsulting\Laravel\MaintenanceMode\DownPayload;

class FileDriver extends BaseDriver implements DriverInterface
{
    /**
     * Get the down file directory.
     *
     * @return string
     */
    protected function downDirectory()
    {
        return dirname($this->downFilePath());
    }

    /**
     * Get the path of the down file.
     *
     * @return string
     */
    protected function downFilePath()
    {
        return $this->config('in_storage_directory')
            ? storage_path($this->config('file_path'))
            : $this->config('file_path');
    }

    /**
     * Activate maintenance mode.
     *
     * @param DownPayload $payload
     * @return bool
     */
    public function activate(DownPayload $payload)
    {
        $this->ensureDirectoryExists();
        $result = file_put_contents($this->downFilePath(), $payload->toJson());

        return is_numeric($result);
    }

    /**
     * Deactivate maintenance mode.
     *
     * @return bool
     */
    public function deactivate()
    {
        if (file_exists($this->downFilePath())) {
            return unlink($this->downFilePath());
        }

        return true;
    }

    /**
     * Check if maintenance mode is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return file_exists($this->downFilePath());
    }

    /**
     * Get the information specified when the site was taken down.
     *
     * @return DownPayload
     */
    public function downPayload()
    {
        $content = file_get_contents($this->downFilePath());

        return DownPayload::fromJson($content);
    }

    /**
     * Make sure that the parent directory of the down file exists.
     *
     * @return void
     */
    protected function ensureDirectoryExists()
    {
        if (! file_exists($this->downDirectory())) {
            mkdir($this->downDirectory(), 0755, true);
        }
    }
}
