<?php

namespace Konsulting\Laravel\MaintenanceMode\Drivers;

class FileDriver implements DriverInterface
{
    /**
     * The path of the maintenance mode indicator file.
     *
     * @var string
     */
    protected $downFileName = 'down';

    protected function getDownDirectory()
    {
        return storage_path('maintenance');
    }

    protected function getDownFilePath()
    {
        return $this->getDownDirectory() . DIRECTORY_SEPARATOR . $this->downFileName;
    }

    /**
     * Activate maintenance mode.
     *
     * @param array $payload
     * @return bool
     */
    public function activate($payload)
    {
        if (! file_exists($this->getDownDirectory())) {
            mkdir($this->getDownDirectory());
        }

        return touch($this->getDownFilePath());
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
}
