# AuditLogger

A simple **Laravel Audit Logging Package** to track database changes on your models.
Tracks **create, update, delete** events and stores only the **changed fields** in `audit_logs` table.

---

## Features

* Logs `created`, `updated`, and `deleted` events automatically
* Only stores changed fields (`old_values` & `new_values`) on updates
* Tracks which user made the changes
* Stores client IP address
* Dynamic observer attachment for all or selected models
* Bootstrap-based view to display audit logs
* Easy to integrate as a Laravel package

---

## Requirements

* Laravel 9 / 10
* PHP >= 8.1
* MySQL or any database supported by Laravel

---

## Installation

Require the package via Composer:

```bash
composer require mahedulhasan/audit-logger
```

> Make sure your `minimum-stability` in `composer.json` allows `dev` packages if needed:

```json
"minimum-stability": "dev",
"prefer-stable": true
```

Run the migrations:

```bash
php artisan migrate
```

(Optional) Publish views/config if you want to customize:

```bash
php artisan vendor:publish --provider="MahedulHasan\AuditLogger\AuditLoggerServiceProvider"
```

---

## Usage

### Observer

The package automatically attaches `AuditObserver` to your models.
To attach manually:

```php
use App\Models\User;
use MahedulHasan\AuditLogger\Observers\AuditObserver;

User::observe(AuditObserver::class);
```

### Display Audit Logs

Visit the route:

```text
/audit-logs
```

This route displays a Bootstrap table showing:

* User
* Event (Created / Updated / Deleted)
* Model & ID
* Changed fields (old → new)
* IP Address
* Timestamp

> Note: The route is automatically registered by the package's ServiceProvider.

---

## Development

* Add new migrations in `database/migrations/`
* Add additional observers in `src/Observers/`
* Update views in `resources/views/`
* Run `composer dump-autoload` if you add new namespaces

---

## Notes

* No local installation needed. The package can be installed directly via Composer.
* Ensure ServiceProvider is registered automatically via `extra.laravel.providers` in `composer.json`.
* Migrations, routes, and views are loaded automatically. You only need to run `php artisan migrate`.

---

## License

MIT License © [Mahedul Hasan]
