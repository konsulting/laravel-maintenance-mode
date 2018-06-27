<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Maintenance mode driver
    |--------------------------------------------------------------------------
    |
    | This is the driver used when activating maintenance mode. For simple
    | single-server configurations, the file driver is sufficient. For
    | multi-server setups, the cache or storage drivers are available.
    |
    | Supported: "file", "storage", "cache"
    |
    */

    'driver' => 'file',


    /*
    |--------------------------------------------------------------------------
    | Driver configuration
    |--------------------------------------------------------------------------
    |
    | This is the configuration for each driver. All drivers need either
    | a file path or key specified, which will be the location at which
    | the down payload is stored.
    |
    */

    'driver_config' => [

        'file' => [
            'file_path'            => 'framework/down',
            'in_storage_directory' => true,
        ],

        'storage' => [
            'file_path' => 'maintenance/down',
            'disk'      => 'default',
        ],

        'cache' => [
            'key' => 'maintenance.down',
        ],
    ],

    'aliases' => [
        'file'    => \Konsulting\Laravel\MaintenanceMode\Drivers\FileDriver::class,
        'storage' => \Konsulting\Laravel\MaintenanceMode\Drivers\StorageDriver::class,
        'cache'   => \Konsulting\Laravel\MaintenanceMode\Drivers\CacheDriver::class,
    ],
];
