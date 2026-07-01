## Overview

This repository uses **Laravel's built-in logging system** powered by the **Monolog** PHP library. The logging configuration follows Laravel conventions with no custom logging implementations or structured logging extensions.

## System/Approach

- **Framework**: Laravel's `Illuminate\Log` facade wrapping Monolog
- **Default channel**: `stack` (configured via `LOG_CHANNEL` environment variable)
- **Log driver**: Single file handler writing to `storage/logs/laravel.log`
- **Log level**: Configurable via `LOG_LEVEL` environment variable, defaults to `debug`
- **Deprecation tracking**: Separate channel (`LOG_DEPRECATIONS_CHANNEL`) for PHP/library deprecation warnings

## Key Files

- **`config/logging.php`** — Central logging configuration defining all available channels and their handlers
- **`storage/logs/laravel.log`** — Primary log output file (859 KB observed, indicating active usage)
- **`.env.example`** — Documents logging-related environment variables: `LOG_CHANNEL`, `LOG_DEPRECATIONS_CHANNEL`, `LOG_LEVEL`
- **`app/Exceptions/Handler.php`** — Exception handler with empty `reportable()` callback; relies on Laravel's default exception reporting to logs

## Architecture and Conventions

### Available Channels

The configuration defines these channels:

| Channel | Driver | Purpose |
|---------|--------|---------|
| `stack` | stack | Default; wraps `single` channel |
| `single` | single | Single file at `storage/logs/laravel.log` |
| `daily` | daily | Rotating daily logs, retained 14 days |
| `slack` | slack | Critical-level alerts to Slack webhook |
| `papertrail` | monolog | Remote syslog via Papertrail |
| `stderr` | monolog | Console output for CLI/testing |
| `syslog` | syslog | System syslog facility |
| `errorlog` | errorlog | PHP error_log |
| `null` | monolog | NullHandler (discards all logs) |
| `emergency` | — | Fallback emergency log path |

### Design Decisions

1. **No custom channels**: The application uses only Laravel's out-of-the-box channel definitions. No domain-specific log channels (e.g., `partner`, `member`, `payment`) are configured.
2. **No structured logging**: The configuration does not enable JSON formatting or custom processors beyond `PsrLogMessageProcessor` (used only in `papertrail` and `stderr` channels). Logs are written in Monolog's default line format.
3. **Placeholder replacement enabled**: The `replace_placeholders` option is set to `true` for most channels, allowing contextual data injection via `{key}` syntax in log messages.
4. **Exception reporting passthrough**: The `Handler::register()` method contains an empty `reportable()` callback, meaning all uncaught exceptions are reported using Laravel's default behavior (logged via the default channel).

### Application-Level Activity Tracking

Separate from the technical logging system, the application implements a **database-backed activity log** via the `ActivityLog` model:

- Stored in the database (Eloquent model)
- Tracks user actions with fields: `user_id`, `activity_type`, `description`, `points_earned`, polymorphic `referenceable`
- Created through `User::addPoints()` for gamification purposes
- This is **not** part of the Monolog logging pipeline; it is a business-domain audit trail

## Rules for Developers

1. **Use the Log facade**: Import via `use Illuminate\Support\Facades\Log;` and call `Log::info()`, `Log::error()`, etc.
2. **Respect log levels**: Use appropriate severity levels (`debug`, `info`, `notice`, `warning`, `error`, `critical`, `alert`, `emergency`). The default threshold is `debug`, so all levels are captured.
3. **No direct file writes**: Do not write directly to `storage/logs/`. Always use the Log facade to respect channel routing.
4. **Environment-driven configuration**: Change log behavior via `.env` variables (`LOG_CHANNEL`, `LOG_LEVEL`) rather than modifying `config/logging.php`.
5. **Distinguish activity logs from system logs**: Use `ActivityLog` model for user-facing audit trails (gamification, user actions). Use `Log` facade for technical debugging, errors, and operational monitoring.
6. **No structured logging convention established**: If adding structured/contextual data, use the placeholder syntax `{key}` which is supported by the `replace_placeholders` setting, or pass context arrays to Log methods.