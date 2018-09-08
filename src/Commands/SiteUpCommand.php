<?php

namespace Konsulting\Laravel\MaintenanceMode\Commands;

use Illuminate\Console\Command;
use Konsulting\Laravel\MaintenanceMode\MaintenanceMode;

class SiteUpCommand extends Command
{
    /**
     * The console command name. Set in the constructor.
     *
     * @var string
     */
    protected $name;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bring the application out of maintenance mode';

    /**
     * The maintenance mode instance.
     *
     * @var MaintenanceMode
     */
    private $maintenanceMode;

    public function __construct(MaintenanceMode $maintenanceMode)
    {
        $this->maintenanceMode = $maintenanceMode;
        $this->name = config('maintenance_mode.override_illuminate_commands') ? 'up' : 'site:up';

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->maintenanceMode->off();

        $this->info('Application is now live.');
    }
}
