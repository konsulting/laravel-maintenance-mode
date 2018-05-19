<?php

namespace Konsulting\Laravel\MaintenanceMode\Drivers;

use Illuminate\Contracts\Filesystem\Factory as Storage;
use Konsulting\Laravel\MaintenanceMode\DownPayload;

class StorageDriver extends BaseDriver implements DriverInterface
{
    /**
     * The storage manager.
     *
     * @var Storage
     */
    protected $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Get the path to the down file.
     *
     * @return string
     */
    public function downFilePath()
    {
        return $this->config['file_path'];
    }

    /**
     * Get the storage disk to use. Return null if default is selected, as the filesystem manager will fetch the
     * default driver automatically when it receives null.
     *
     * @return string|null
     */
    public function storageDisk()
    {
        return $this->config['disk'] === 'default'
            ? null
            : $this->config['disk'];
    }

    /**
     * Get the storage filesystem object with disk selected.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function storage()
    {
        return $this->storage->disk($this->storageDisk());
    }

    /**
     * Activate maintenance mode.
     *
     * @param DownPayload $payload
     * @return bool
     */
    public function activate(DownPayload $payload)
    {
        return $this->storage()->put($this->downFilePath(), $payload->toJson());
    }

    /**
     * Deactivate maintenance mode.
     *
     * @return bool
     */
    public function deactivate()
    {
        return $this->storage()->delete($this->downFilePath());
    }

    /**
     * Check if maintenance mode is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->storage()->exists($this->downFilePath());
    }

    /**
     * Get the information specified when the site was taken down.
     *
     * @return DownPayload
     */
    public function downPayload()
    {
        $content = $this->storage()->get($this->downFilePath());

        return DownPayload::fromJson($content);
    }
}
