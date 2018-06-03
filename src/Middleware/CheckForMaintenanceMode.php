<?php

namespace Konsulting\Laravel\MaintenanceMode\Middleware;

use Closure;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Request;
use Konsulting\Laravel\MaintenanceMode\DownPayload;
use Konsulting\Laravel\MaintenanceMode\MaintenanceMode;
use Symfony\Component\HttpFoundation\IpUtils;

class CheckForMaintenanceMode
{
    /**
     * @var MaintenanceMode
     */
    protected $maintenanceMode;

    public function __construct()
    {
        $this->maintenanceMode = app(MaintenanceMode::class);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure                  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($this->maintenanceMode->isOn()) {
            $data = $this->maintenanceMode->getDownInformation();

            if (! $this->isAllowedIp($request, $data)) {
                throw new MaintenanceModeException(
                    $data->getTime()->getTimestamp(), $data->getRetryTime(), $data->getMessage());
            }
        }

        return $next($request);
    }

    /**
     * Check if the request is from an allowed address.
     *
     * @param Request     $request
     * @param DownPayload $data
     * @return bool
     */
    protected function isAllowedIp($request, $data): bool
    {
        return IpUtils::checkIp($request->ip(), $data->getAllowedAddresses());
    }
}
