<?php

namespace Konsulting\Laravel\MaintenanceMode;

use Carbon\Carbon;

class DownPayload
{
    /**
     * The message given when the site was taken down.
     *
     * @var string
     */
    protected $message;

    /**
     * The time that the site was taken down.
     *
     * @var Carbon
     */
    protected $time;

    /**
     * The number of seconds before the request can be retried.
     *
     * @var int
     */
    protected $secondsToRetry;

    /**
     * The IP addresses allowed to access the site whilst maintenance mode is active.
     *
     * @var string[]
     */
    protected $allowedAddresses;

    public function __construct(array $payload)
    {
        $this->time = array_key_exists('time', $payload)
            ? Carbon::createFromTimestamp($payload['time'])
            : null;
        $this->message = $payload['message'] ?? '';
        $this->secondsToRetry = $payload['retry'] ?? 0;
        $this->allowedAddresses = $payload['allowed'] ?? [];
    }

    /**
     * Construct a new payload object from JSON.
     *
     * @param string $content
     * @return DownPayload
     */
    public static function fromJson($content)
    {
        return new static(json_decode($content, true));
    }

    /**
     * Get the timestamp as a Carbon instance.
     *
     * @return Carbon|null
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Get the message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get the number of seconds before the request can be retried.
     *
     * @return int
     */
    public function getRetryTime()
    {
        return $this->secondsToRetry;
    }

    /**
     * Get the allowed IP addresses.
     *
     * @return string[]
     */
    public function getAllowedAddresses()
    {
        return $this->allowedAddresses;
    }

    /**
     * Get the payload array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'time'    => $this->time->getTimestamp(),
            'message' => $this->message,
            'retry'   => $this->secondsToRetry,
            'allowed' => $this->allowedAddresses,
        ];
    }

    /**
     * Get the payload array as JSON.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
}
