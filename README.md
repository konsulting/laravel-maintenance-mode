# Laravel Maintenance Mode

## Installation
Install via Composer:

```
composer require konsulting/laravel-maintenance-mode
```

Laravel 5.5+ will automatically discover the provider and facade.

### Config
Publish the config file with:
```
php artisan vendor:publish --provider="Konsulting\Laravel\MaintenanceMode\MaintenanceModeProvider"
```

### Middleware
The file driver is compatible with Laravel's existing maintenance mode middleware when using the default configuration values.

To use a different driver or a different file path, add the provided `CheckForMaintenanceMode` middleware to `app/Http/Kernel.php`.

```php
class Kernel extends HttpKernel
{
    // ...
    protected $middleware = [
        \Konsulting\Laravel\MaintenanceMode\Middleware\CheckForMaintenanceMode::class,
        
        // You can remove the Illuminate CheckForMaintenanceMode middleware or leave it in
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        // ...
    ];
}
```

## Basic usage
To **activate** maintenance mode:
```
php artisan site:down
```

To **deactivate** maintenance mode:
```
php artisan site:up
```

The behaviour of maintenance mode is similar to Laravel's `artisan up`/`artisan down` commands. 
When activated, the a payload containing information about the outage is stored using the chosen driver.
This is picked up by the middleware, which throws `Illuminate\Foundation\Http\Exceptions\MaintenanceModeException` if maintenance mode is active.
Laravel's exception handler deals with returning the correct response.

### Drivers
By default there are three drivers included: file, cache and storage.
The file driver is very similar to Laravel's built in maintenance mode in that it stores a payload file in the local filesystem.
The cache and storage drivers are useful for when multiple webservers are serving the application, as they can be easily configured to store the payload at an external location.

See the configuration file for more information about the drivers.

## Programmatic usage
### Instantiation
The available drivers are: cache, file, and storage. More detail is contained in `config/maintenance_mode.php`.

```php
$driver = new FileDriver(['file_path' => '/path/to/file']);
$maintenanceMode = new MaintenanceMode($driver);
```

### Activating maintenance mode
Maintenance mode is activated with the `MaintenanceMode::on()` method.

When maintenance mode is activated, a payload object is created with the information given and stored by the chosen driver.
This contains information about the site being taken down, including the at which maintenance mode was activated, the
seconds between retry requests, any IP addresses that are allowed to access the site during maintenance mode, and a
message associated with the downtime.

```php
$message = 'Down for database upgrades.';
$allowedIps = ['192.168.10.10', '127.0.0.1'];
$secondsToRetry = 60;

$maintenanceMode->on($message, $allowedIps, $secondsToRetry);

$maintenanceMode->isOn(); // true
```

### Retrieving down information
```php
$retrievedPayload = $maintenanceMode->getDownInformation();
// new DownPayload([
//     'time'    => Carbon::now()->getTimestamp(),
//     'message' => 'Down for database upgrades.',
//     'retry'   => 60,
//     'allowed' => ['192.168.10.10', '127.0.0.1'],
// ]);
```

### Deactivating maintenance mode
Maintenance mode is deactivated with the `MaintenanceMode::off()` method.
