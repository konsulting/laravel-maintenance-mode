<?php

namespace Konsulting\Laravel\MaintenanceMode\Drivers;

use Cache;
use Konsulting\Laravel\MaintenanceMode\DownPayload;

class CacheDriver extends BaseDriver implements DriverInterface
{
    /**
     * The cache key.
     *
     * @return string
     */
    public function key()
    {
        return $this->config['key'];
    }

    /**
     * Activate maintenance mode.
     *
     * @param DownPayload $payload
     * @return bool
     */
    public function activate(DownPayload $payload)
    {
        return Cache::forever($this->key(), $payload->toArray());
    }

    /**
     * Deactivate maintenance mode.
     *
     * @return bool
     */
    public function deactivate()
    {
        return Cache::forget($this->key());
    }

    /**
     * Check if maintenance mode is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return Cache::has($this->key());
    }

    /**
     * Get the information specified when the site was taken down.
     *
     * @return DownPayload
     */
    public function downPayload()
    {
        $content = Cache::get($this->key());

        return new DownPayload($content);
    }
}
