This repository uses a standard Laravel 10 dependency management strategy, leveraging **Composer** for PHP packages and **NPM** (via Vite) for frontend assets.

### PHP Dependencies (Composer)
- **Manager**: Composer (`composer.json`, `composer.lock`).
- **Core Framework**: `laravel/framework` (^10.10).
- **Key Libraries**:
  - `laravel/sanctum` (^3.3): For API authentication and session management.
  - `guzzlehttp/guzzle` (^7.2): For HTTP client requests.
  - `laravel/tinker` (^2.8): For interactive debugging.
- **Dev Dependencies**: Includes `phpunit/phpunit` (^10.1), `fakerphp/faker`, `laravel/pint` (code style), and `spatie/laravel-ignition` (error page).
- **Autoloading**: PSR-4 autoloading is configured for `App\` (app/), `Database\Factories\`, and `Database\Seeders\`.
- **Stability**: Configured to prefer stable packages (`prefer-stable`: true).

### Frontend Dependencies (NPM/Vite)
- **Manager**: NPM (`package.json`).
- **Build Tool**: Vite (^5.0.0) with the `laravel-vite-plugin` (^1.0.0).
- **Libraries**: `axios` (^1.6.4) for HTTP requests.
- **Scripts**: `dev` (starts Vite dev server) and `build` (compiles assets for production).

### Conventions & Rules
- **Lockfiles**: Both `composer.lock` and `package-lock.json` (implied by NPM usage) should be committed to ensure deterministic builds.
- **Environment Configuration**: Third-party service credentials (e.g., Mailgun, AWS) are managed in `config/services.php` and sourced from `.env`.
- **Vendor Directory**: The `vendor/` directory is present but typically ignored in version control (standard Composer practice), relying on `composer install` to restore dependencies.
- **Service Providers**: New packages are auto-discovered via Laravel's package discovery mechanism, as seen in `composer.json` scripts (`@php artisan package:discover`).