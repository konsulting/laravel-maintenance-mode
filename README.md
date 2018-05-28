# Laravel Maintenance Mode

## Installation
Install via Composer:

```
composer require konsulting/laravel-maintenance-mode
```

## Usage
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
