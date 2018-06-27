<?php

namespace Konsulting\Laravel\MaintenanceMode\Drivers;

use Illuminate\Contracts\Cache\Repository as Cache;
use Konsulting\Laravel\MaintenanceMode\DownPayload;

class CacheDriver extends BaseDriver implements DriverInterface
{
    /**
     * @var Cache
     */
    protected $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * The cache key.
     *
     * @return string
     */
    public function key()
    {
        return $this->config('key');
    }

    /**
     * Activate maintenance mode.
     *
     * @param DownPayload $payload
     * @return bool
     */
    public function activate(DownPayload $payload)
    {
        $this->cache->forever($this->key(), $payload->toArray());

        return true;
    }

    /**
     * Deactivate maintenance mode.
     *
     * @return bool
     */
    public function deactivate()
    {
        return $this->cache->forget($this->key());
    }

    /**
     * Check if maintenance mode is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->cache->has($this->key());
    }

    /**
     * Get the information specified when the site was taken down.
     *
     * @return DownPayload
     */
    public function downPayload()
    {
        $content = $this->cache->get($this->key());

        return new DownPayload($content);
    }
}
