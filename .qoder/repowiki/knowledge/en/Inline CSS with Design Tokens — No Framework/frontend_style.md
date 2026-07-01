## Overview

This Laravel-based multi-vendor thrift marketplace uses **inline `<style>` blocks** in every Blade template for all styling. There is **no CSS framework** (Tailwind, Bootstrap, etc.) installed or configured. The build pipeline (Vite) is present but unused for styling — `resources/css/app.css` is empty.

## System / Approach

### Inline CSS per Template
Every view file (`*.blade.php`) contains its own `<style>` block in the `<head>`. This includes:
- Public-facing pages: landing, catalog, articles, community, about
- Member auth pages: login, register, forgot-password, profile
- Partner dashboard and management pages
- Admin dashboard and CRUD pages

### Design Token Pattern via CSS Custom Properties
Public-facing templates share a consistent set of **CSS custom properties** defined in `:root`:

```css
:root {
  --bg: #f7f7f5;
  --text: #111111;
  --muted: #6b7280;
  --card: #ffffff;
  --accent: #dc2626;
  --accent-dark: #b91c1c;
  --border: #e5e7eb;
  --dark: #111827;
}
```

These tokens are reused across public views (`landing.blade.php`, `catalog/index.blade.php`, `catalog/about.blade.php`, etc.) to maintain visual consistency for colors.

Admin and member auth pages use **hardcoded hex values** instead of CSS variables, creating a visual divergence from the public theme.

### Typography
- Font stack: `-apple-system, "Helvetica Neue", Arial, sans-serif` (system fonts only)
- No web font imports in application views (the default `welcome.blade.php` loads Figtree from Google Fonts, but this is the Laravel scaffold page, not part of the app)
- Heavy use of `font-weight: 900` for headings and brand text
- Letter-spacing used decoratively on eyebrow labels and brand names

### Responsive Strategy
- Media queries at `max-width: 960px`, `max-width: 900px`, and `max-width: 640px` handle mobile breakpoints
- Grid layouts collapse from multi-column to single-column on smaller screens
- Navigation hides on mobile (`display: none`)
- Use of `clamp()` for fluid typography on hero titles

### Layout Patterns
- **Container width**: `width: min(1280px, 94%)` with `margin: 0 auto` for centered content
- **Grid systems**: CSS Grid with `repeat(auto-fill, minmax(...))` for product cards, article cards, feature cards, UGC grids
- **Flexbox**: Used for navigation bars, card internals, action buttons, stat rows
- **Sticky topbar**: `position: sticky; top: 0` with `backdrop-filter: blur(8px)` for glassmorphism effect

## Key Files

| File | Role |
|------|------|
| `resources/views/public/landing.blade.php` | Primary public page with full design token definitions and section styles |
| `resources/views/partials/public-nav-styles.blade.php` | Extracted nav styles (partial), reuses same token pattern |
| `resources/views/catalog/index.blade.php` | Product catalog grid with shared tokens |
| `resources/views/catalog/about.blade.php` | About page with shared tokens |
| `resources/views/admin/dashboard.blade.php` | Admin panel with hardcoded dark sidebar theme |
| `resources/views/member/login.blade.php` | Auth page with standalone inline styles |
| `resources/css/app.css` | Empty — Vite entry point not utilized for CSS |
| `package.json` | No CSS-related dependencies (no Tailwind, PostCSS, Sass) |
| `vite.config.js` | Configured for Laravel Vite plugin but CSS pipeline is inert |

## Architecture & Conventions

### Three Visual Themes
1. **Public theme** (landing, catalog, articles, community): Light background (`#f7f7f5`), red accent (`#dc2626`), CSS custom properties
2. **Admin theme**: Dark sidebar (`#0f172a`), light content area (`#f3f4f6`), hardcoded colors, no CSS variables
3. **Auth theme** (member/partner login/register): Centered card layout on gray background, hardcoded colors

### No Component Reuse for Styles
- Each blade template duplicates its `<style>` block entirely
- No shared CSS file is imported
- The `public-nav-styles.blade.php` partial exists but only covers nav-specific rules; other sections are duplicated across templates

### Build Pipeline Status
- Vite is configured (`vite.config.js`) with `resources/css/app.css` and `resources/js/app.js` as inputs
- `resources/css/app.css` is empty — all styling bypasses the build tool
- `package.json` has no CSS tooling dependencies (no `tailwindcss`, `postcss`, `sass`, `autoprefixer`)

## Rules Developers Should Follow

1. **Add styles inline** — Every new Blade template must include its own `<style>` block in `<head>`. Do not expect a global stylesheet.
2. **Use design tokens for public pages** — Define `:root` CSS custom properties (`--bg`, `--text`, `--accent`, `--border`, `--card`, `--muted`, `--dark`) at the top of the style block for any public-facing view to maintain color consistency.
3. **Admin/auth pages use hardcoded colors** — These sections intentionally diverge from the public theme. Match existing admin/member templates for visual consistency within those areas.
4. **Responsive breakpoints** — Use `@media(max-width:960px)` for tablet and `@media(max-width:640px)` for mobile. Hide non-essential nav elements on small screens.
5. **No external CSS frameworks** — Do not install Tailwind, Bootstrap, or similar. All styling is vanilla CSS.
6. **Container pattern** — Use `width: min(1280px, 94%); margin: 0 auto;` for section containers.
7. **Font stack** — Stick to `-apple-system, "Helvetica Neue", Arial, sans-serif`. Do not add web font imports unless explicitly required.
8. **Vite CSS entry is unused** — Do not place meaningful CSS in `resources/css/app.css`; it will not be picked up by any template.