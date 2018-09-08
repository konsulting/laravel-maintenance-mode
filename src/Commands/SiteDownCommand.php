<?php

namespace Konsulting\Laravel\MaintenanceMode\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\InteractsWithTime;
use Konsulting\Laravel\MaintenanceMode\MaintenanceMode;

class SiteDownCommand extends Command
{
    use InteractsWithTime;

    /**
     * The console command signature. Set in the constructor.
     *
     * @var string
     */
    protected $signature;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put the application into maintenance mode';

    /**
     * The maintenance mode instance.
     *
     * @var MaintenanceMode
     */
    private $maintenanceMode;

    public function __construct(MaintenanceMode $maintenanceMode)
    {
        $this->maintenanceMode = $maintenanceMode;
        $this->signature = $this->getSignature();

        parent::__construct();
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    protected function getSignature()
    {
        $name = config('maintenance_mode.override_illuminate_commands') ? 'down' : 'site:down';

        return $name . '
                    {--message= : The message for the maintenance mode. }
                    {--retry= : The number of seconds after which the request may be retried.}
                    {--allow=* : IP or networks allowed to access the application while in maintenance mode.}';
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->maintenanceMode->on(
            $this->option('message'),
            $this->option('allow'),
            $this->getRetryTime()
        );

        $this->comment('Application is now in maintenance mode.');
    }

    /**
     * Get the number of seconds the client should wait before retrying their request.
     *
     * @return int|null
     */
    protected function getRetryTime()
    {
        $retry = $this->option('retry');

        return is_numeric($retry) && $retry > 0 ? (int) $retry : null;
    }
}
