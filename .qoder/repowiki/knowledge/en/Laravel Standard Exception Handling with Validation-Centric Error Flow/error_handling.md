## Overview

This Laravel-based marketplace platform uses **Laravel's built-in exception handling framework** as its primary error management system. The application relies on conventional Laravel patterns rather than custom error types or specialized error handling infrastructure.

## Core Architecture

### Exception Handler (`app/Exceptions/Handler.php`)

The application extends `Illuminate\Foundation\Exceptions\Handler` with minimal customization:
- **No custom exception classes** are defined in the codebase
- The `register()` method contains an empty `reportable()` callback, indicating no custom exception reporting logic
- Uses Laravel's default `$dontFlash` array to prevent sensitive fields (passwords) from being flashed to session on validation failures
- Registered as singleton in `bootstrap/app.php` via `Illuminate\Contracts\Debug\ExceptionHandler::class`

### Error Propagation Strategy

The codebase follows three distinct error handling patterns:

1. **Validation Errors (Primary Pattern)**
   - Controllers use `$request->validate([...])` which automatically throws `Illuminate\Validation\ValidationException` on failure
   - Laravel's middleware stack (`ShareErrorsFromSession`) makes these errors available to views
   - Example: `AdminArticleController::store()` validates article data; failed validation redirects back with errors

2. **Authentication/Authorization Errors via Middleware**
   - Custom middleware (`EnsurePartnerAuthenticated`, `EnsureMemberAuthenticated`, `EnsureAdminAuthenticated`) handles auth failures by redirecting with `withErrors()`
   - Errors are attached to specific fields (e.g., `'email' => 'Akun mitra Anda belum disetujui...'`)
   - Failed authentication triggers logout before redirect to prevent stale sessions

3. **Manual Validation Exceptions**
   - One instance found: `AdminArticleController::resolveCoverImage()` explicitly throws `ValidationException::withMessages()` for invalid image URLs
   - This is the only place in the codebase where exceptions are manually thrown rather than relying on automatic validation

## Key Conventions

### No Try-Catch Blocks

The entire codebase contains **zero try-catch blocks**. This indicates:
- Reliance on Laravel's global exception handler for uncaught exceptions
- No local error recovery or graceful degradation strategies
- Database operations, file uploads, and external calls proceed without explicit error handling

### Error Presentation Pattern

All user-facing errors follow a consistent pattern:
```php
return back()->withErrors(['field_name' => 'Error message in Indonesian']);
```

Key characteristics:
- Errors are always field-keyed arrays (compatible with Laravel's validation error display)
- Messages are in Indonesian (Bahasa Indonesia)
- Redirects use `back()` to return to the previous page, preserving form state
- Success messages use `->with('success', '...')` flash data

### No HTTP Status Code Manipulation

- No `abort()` calls found anywhere in the codebase
- No custom HTTP exception throwing (404, 403, 500, etc.)
- All error responses are redirects (302) with flashed error messages
- This means API consumers or programmatic clients receive HTML redirects rather than structured error responses

### Middleware Error Handling

The `Kernel.php` registers standard Laravel middleware including:
- `ValidatePostSize` — rejects oversized POST requests
- `TrimStrings` / `ConvertEmptyStringsToNull` — normalizes input
- `ShareErrorsFromSession` — makes validation errors available to Blade templates
- Role-specific auth middleware (`admin.auth`, `partner.auth`, `member.auth`) that redirect on failure

## Developer Rules

Based on observed patterns, developers should:

1. **Use `$request->validate()` for all input validation** — never manually check and throw exceptions
2. **Return `back()->withErrors([...])` for business logic failures** — maintain consistency with auth controllers
3. **Do not use try-catch** — let Laravel's global handler catch unexpected exceptions
4. **Write error messages in Indonesian** — all existing messages follow this convention
5. **Key errors to the relevant form field** — enables Laravel's automatic error display in Blade templates
6. **Logout before redirecting on auth failures** — prevents stale authenticated sessions (seen in partner auth)
7. **Use `throw ValidationException::withMessages()` only when validation rules cannot express the constraint** — the single usage is for URL format validation that goes beyond standard rules

## Gaps and Limitations

- **No custom exception types** — all errors are either validation exceptions or generic PHP exceptions
- **No error logging customization** — the empty `reportable()` callback means no additional context is added to logged exceptions
- **No API error responses** — the `api` middleware group exists but no controllers return JSON error responses
- **No error views** — `resources/views/errors/` directory does not exist, meaning Laravel's default error pages are used for unhandled exceptions
- **No retry or fallback mechanisms** — file operations, database queries, and external calls have no error recovery