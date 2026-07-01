## Overview

This repository uses **Laravel's standard configuration system**, which employs a two-layer approach:

1. **Environment variables** (`.env` file) for secrets, credentials, and deployment-specific values
2. **PHP configuration files** (`config/*.php`) that read from `env()` helpers and provide structured defaults

The framework loads `.env` at bootstrap time via `bootstrap/app.php`, then each config file is evaluated to build the application's configuration registry accessible via the global `config()` helper.

---

## Key Files

### Environment Layer
- **`.env`** — Runtime environment variables (not committed; copied from `.env.example`)
- **`.env.example`** — Template documenting all expected environment variables with sensible defaults

### Core Config Files (`config/`)
| File | Purpose |
|------|---------|
| `app.php` | Application name, debug mode, timezone, locale, service providers, aliases |
| `database.php` | Database connections (MySQL default), Redis cache/session settings |
| `filesystems.php` | Storage disks (local, public, S3), symbolic link mapping |
| `auth.php` | Authentication guards, password reset, rate limiting |
| `session.php` | Session driver, lifetime, cookie settings |
| `cache.php` | Cache stores and drivers |
| `mail.php` | Mailer configuration (SMTP, Mailgun, etc.) |
| `logging.php` | Log channels and levels |
| `queue.php` | Queue connection and job settings |
| `sanctum.php` | API token authentication settings |
| `cors.php` | Cross-origin request policies |
| `services.php` | Third-party service credentials (Mailgun, Postmark, AWS SES) |

### Custom Domain Configs
| File | Purpose |
|------|---------|
| `catalog.php` | Store branding (name, tagline, social links), product type taxonomy, brand storytelling content, size chart templates, sample product catalog |
| `admin.php` | Admin panel entry path, default admin credentials (dev-only) |
| `brands.php` | Brand logo path mappings for UI chip filters (static asset references) |

---

## Architecture & Conventions

### Two-Layer Loading Pattern
Every config value follows this pattern:
```php
'key' => env('ENV_VAR_NAME', 'default_value'),
```
- `env()` reads from the `.env` file
- The second argument provides a fallback if the env var is missing
- Config files are cached in production via `php artisan config:cache`, which means `env()` calls outside config files return `null`

### Custom Config Structure
Domain-specific configs (`catalog.php`, `admin.php`, `brands.php`) follow Laravel conventions:
- Return associative arrays
- Use `env()` for deploy-time overrides
- Provide rich defaults inline (e.g., `catalog.php` embeds product type definitions with labels, emojis, and pairing rules)
- Are accessed via dot notation: `config('catalog.store_name')`, `config('brands.Nike')`

### Usage Pattern in Controllers
Controllers consistently pass config values to views as view data:
```php
'storeName' => config('catalog.store_name'),
'productTypes' => config('catalog.product_types', []),
```
This keeps config access centralized and testable.

### Secrets Management
- Database credentials, mail credentials, AWS keys, and admin passwords are **exclusively** defined in `.env`
- `.env` is gitignored; `.env.example` serves as the documentation template
- No secrets are hardcoded in config files — all sensitive values use `env()` without defaults or with `null` defaults

### Static Asset Configuration
`brands.php` maps brand names to relative asset paths under `public/icons/brands/`. This is a **code-driven** approach rather than database-driven, requiring manual updates when new brands are added.

---

## Rules for Developers

1. **Never commit `.env`** — Only modify `.env.example` when adding new environment variables
2. **Use `env()` only in config files** — After `config:cache` is run in production, `env()` returns `null` everywhere except config files
3. **Add new config keys to `.env.example`** — Document every new env var with its purpose and default
4. **Access config via `config()` helper** — Use dot notation (`config('catalog.social_links.instagram')`) rather than direct `env()` calls in application code
5. **Custom configs belong in `config/`** — Create new PHP files returning arrays for domain-specific settings (e.g., `config/payments.php`)
6. **Brand logos require dual updates** — Adding a new brand requires both a database entry AND an entry in `config/brands.php` plus the asset file in `public/icons/brands/`
7. **Admin credentials in `.env` are dev-only** — The `ADMIN_USERNAME`/`ADMIN_PASSWORD` pattern in `config/admin.php` is suitable for local development but should be replaced with proper user management in production