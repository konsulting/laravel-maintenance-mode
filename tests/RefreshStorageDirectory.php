<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests;

trait RefreshStorageDirectory
{
    /**
     * Before the test, create a copy of the Laravel storage directory. After the test, restore the storage directory
     * from the backup.
     *
     * @return void
     */
    protected function refreshStorageDirectory()
    {
        \File::copyDirectory(base_path('storage'), base_path('storage_backup'));

        $this->beforeApplicationDestroyed(function () {
            \File::deleteDirectory(base_path('storage'));
            \File::copyDirectory(base_path('storage_backup'), base_path('storage'));
            \File::deleteDirectory(base_path('storage_backup'));
        });
    }
}
