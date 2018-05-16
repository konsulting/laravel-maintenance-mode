<?php

namespace Konsulting\Laravel\MaintenanceMode\Drivers;

use Konsulting\Laravel\MaintenanceMode\DownPayload;

class FileDriver implements DriverInterface
{
    /**
     * Get the down file directory.
     *
     * @return string
     */
    protected function getDownDirectory()
    {
        return storage_path('maintenance');
    }

    /**
     * Get the path of the down file.
     *
     * @return string
     */
    protected function getDownFilePath()
    {
        return $this->getDownDirectory() . DIRECTORY_SEPARATOR . 'down';
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
        $result = file_put_contents($this->getDownFilePath(), $payload->toJson());

        return is_numeric($result);
    }

    /**
     * Deactivate maintenance mode.
     *
     * @return bool
     */
    public function deactivate()
    {
        if (file_exists($this->getDownFilePath())) {
            return unlink($this->getDownFilePath());
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
        return file_exists($this->getDownFilePath());
    }

    /**
     * Get the information specified when the site was taken down.
     *
     * @return DownPayload
     */
    public function downPayload()
    {
        $content = file_get_contents($this->getDownFilePath());

        return DownPayload::fromJson($content);
    }

    /**
     * Make sure that the parent directory of the down file exists.
     *
     * @return void
     */
    protected function ensureDirectoryExists()
    {
        if (! file_exists($this->getDownDirectory())) {
            mkdir($this->getDownDirectory());
        }
    }
}
