The project utilizes the standard Laravel 10 build and development ecosystem, relying on Composer for PHP dependency management and Vite (via `laravel-vite-plugin`) for frontend asset compilation. There are no custom build scripts, Makefiles, or containerization configurations (Docker/Kubernetes) present in the repository root, indicating a reliance on default Laravel conventions and potentially local development environments or external deployment platforms.

### Core Build Tools
- **Dependency Management**: 
  - **PHP**: Managed via `composer.json`. Key dependencies include `laravel/framework` (^10.10) and `laravel/sanctum` for API authentication. Dev dependencies include `phpunit`, `laravel/pint` (code style), and `laravel/sail` (local Docker environment, though no custom `docker-compose.yml` is visible in the root tree, suggesting default Sail usage or manual setup).
  - **JavaScript/CSS**: Managed via `package.json`. Uses `vite` (^5.0.0) and `laravel-vite-plugin` for bundling `resources/css/app.css` and `resources/js/app.js`.

### Build & Development Scripts
- **Frontend**: 
  - `npm run dev`: Starts the Vite development server with HMR (Hot Module Replacement).
  - `npm run build`: Compiles assets for production using Vite.
- **Backend**: 
  - No custom `scripts` defined in `composer.json` beyond standard Laravel post-install/update hooks (e.g., `package:discover`, `key:generate`).
  - Database migrations and seeding are handled via the standard `artisan` CLI (`php artisan migrate`, `php artisan db:seed`).

### Testing
- **Framework**: PHPUnit ^10.1.
- **Configuration**: `phpunit.xml` is configured for both Unit and Feature tests. It sets specific environment variables for testing (e.g., `CACHE_DRIVER=array`, `SESSION_DRIVER=array`, `QUEUE_CONNECTION=sync`) to ensure isolated and fast test execution. SQLite in-memory database is commented out, suggesting a MySQL/PostgreSQL connection might be used even in testing or requires manual uncommenting.

### Deployment & CI/CD
- **CI/CD**: No GitHub Actions, GitLab CI, or other CI configuration files were found in the repository root. 
- **Containerization**: No `Dockerfile` or `docker-compose.yml` exists in the root. The presence of `laravel/sail` in `composer.json` suggests Docker *can* be used, but the configuration is not committed or customized in this view.
- **Release Flow**: No versioning tags, release scripts, or changelog management tools are evident. Deployment likely involves manual `git pull` followed by `composer install --optimize-autoloader`, `npm run build`, and `php artisan migrate` on the target server.

### Developer Conventions
- **Asset Compilation**: Developers must run `npm run dev` during local development to serve assets via Vite. For production, `npm run build` must be executed before deployment to generate static assets in `public/build/`.
- **Environment Setup**: New installations rely on `composer install` triggering `.env` creation from `.env.example` and key generation via Composer scripts.
- **Code Style**: `laravel/pint` is included, suggesting developers should run `./vendor/bin/pint` to enforce PSR-12/Laravel style guidelines, though no pre-commit hooks are visible.