# Arab‑Med Platform

A modern RTL-first medical education platform built with Laravel, Vite, and a modular frontend architecture. This repository powers public (guest) pages, authentication, and the application experience for learners and admins.

## Table of Contents
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Architecture Overview](#architecture-overview)
- [Project Structure](#project-structure)
- [Getting Started](#getting-started)
- [Environment Configuration](#environment-configuration)
- [Database and Seeding](#database-and-seeding)
- [Running and Building](#running-and-building)
- [Frontend Assets](#frontend-assets)
- [Theming and Design System](#theming-and-design-system)
- [Layouts and Blade Components](#layouts-and-blade-components)
- [Internationalization (RTL/Arabic)](#internationalization-rtlarabic)
- [Accessibility](#accessibility)
- [Testing](#testing)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)
- [Common Tasks](#common-tasks)
- [Contributing](#contributing)
- [License](#license)

## Features
- RTL-first Arabic UI with full responsive design
- Modular Blade layouts: guest, auth, and app
- Reusable Blade components: navbar, sidebar, modal, table, form, card, etc.
- Material-inspired design system with modern glass/gradient treatments
- Vite-powered asset pipeline with page-specific bundles
- SEO-ready guest pages with OpenGraph/Twitter meta and structured data
- Cookie consent and newsletter subscription
- Role-ready navigation and sections for future admin features
- Test scaffolding for unit and feature tests

## Tech Stack
- Backend: Laravel 10+
- Frontend: Vite, vanilla JS (progressive), Material Icons, Google Fonts
- Styling: Custom CSS modules with theme tokens, RTL support
- Database: MySQL/MariaDB/PostgreSQL (via Laravel database configuration)
- Testing: PHPUnit

## Architecture Overview
- Laravel app with a clean separation of concerns: routes, controllers, requests, models.
- Frontend organized by purpose: `layouts/`, `components/`, `pages/`, with a `theme.css` providing cross-cutting variables and tokens.
- Blade components encapsulate UI primitives and complex widgets with accessibility baked-in.
- Page-specific CSS/JS loaded only where needed using `@vite([...])` in Blade.

## Project Structure
```
app/
  Http/ Controllers/ Middleware/ Requests/
  Models/
resources/
  css/
    app.css               # Entrypoint importing theme and shared styles
    theme.css             # Design tokens & base styles (colors, spacing, shadows, etc.)
    layouts/              # Layout-specific styles (guest, auth, app)
    components/           # Navbar, table, modals, etc.
    pages/                # Page bundles (e.g., welcome.css)
  js/
    app.js                # Global JS (if any) + layout initializers
    layouts/              # guest.js, etc.
    pages/                # Page scripts (e.g., welcome.js)
  views/
    layouts/guest.blade.php  # Guest/public layout
    welcome.blade.php        # Landing page
    components/              # Blade components (x-navbar, x-modal, ...)
routes/
  web.php api.php
```

## Getting Started
1. Clone the repository and install PHP dependencies:
```bash
composer install
```
2. Install Node dependencies:
```bash
npm install
```
3. Create your environment file and app key:
```bash
cp .env.example .env
php artisan key:generate
```
4. Configure your database in `.env` (see [Environment Configuration](#environment-configuration)).
5. Run migrations and seeders:
```bash
php artisan migrate --seed
```
6. Start the dev servers (PHP + Vite):
```bash
php artisan serve
npm run dev
```
Open http://127.0.0.1:8000 in your browser.

## Environment Configuration
Key `.env` variables:
```
APP_NAME="الطب العربي"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
APP_LOCALE=ar
APP_FALLBACK_LOCALE=ar

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=arabmed
DB_USERNAME=root
DB_PASSWORD=
```
After changing `.env` run:
```bash
php artisan config:clear
php artisan config:cache
```

## Database and Seeding
- Migrations live in `database/migrations/`.
- Seeders in `database/seeders/` provide initial data (e.g., admin user, categories).
- Example commands:
```bash
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed
```

## Running and Building
- Development (with HMR):
```bash
npm run dev
```
- Production build:
```bash
npm run build
```
- Laravel server:
```bash
php artisan serve
```
Vite is configured in `vite.config.js`. Blade pages reference bundles with `@vite([...])`.

## Frontend Assets
- Entry: `resources/css/app.css` imports theme and shared styles.
- Page-specific CSS/JS (e.g., `resources/css/pages/welcome.css`, `resources/js/pages/welcome.js`) are included only on the pages that need them.
- Component CSS in `resources/css/components/` (e.g., `navbar.css`, `modals.css`, `table.css`).
- Layout CSS in `resources/css/layouts/` (e.g., `guest.css`).
- Images: `public/images/` (e.g., `logo.png`, `logo-white.png`, favicons).

### Logos and Favicons
- Place your logos under `public/images/`:
  - `logo.svg` or `logo.png` (navbar)
  - `logo-white.png` (dark footer)
- Favicons:
  - `public/favicon.ico`, `public/images/favicon-32x32.png`, `public/images/favicon-16x16.png`, `public/images/apple-touch-icon.png`
- The navbar uses `brand-logo="{{ asset('images/logo.png') }}"` and the footer references `logo-white.png`.

## Theming and Design System
- All tokens are defined in `resources/css/theme.css`:
  - Colors: `--primary`, `--secondary`, `--success`, `--warning`, `--error`, `--info` (+ scale 50-900)
  - Neutrals and surfaces: `--bg-color`, `--surface`, `--border-color`, `--text-color`
  - Typography scale, spacing, radii, shadows, elevations
  - RTL-aware focus, selection, scrollbar styling
- Modern brand gradients and glass tokens:
  - `--brand-gradient-extended`: a blue/teal gradient used for hero, CTA, and dark sections
  - `--glass-bg`, `--glass-border`, `--glass-blur`, `--glass-elevation`: for transparent nav and panels
- Update tokens in `theme.css` to change the app look globally.

## Layouts and Blade Components
- Layouts:
  - `resources/views/layouts/guest.blade.php` – public pages, SEO meta, structured data, navbar, footer, cookie banner.
  - (Auth and app layouts follow the same modular approach.)
- Key Components (in `resources/views/components/`):
  - `x-navbar`: brand logo/text, navigation slots, transparent vs solid, auto solid on scroll.
  - `x-sidebar`: collapsible, role-ready sections (if present).
  - `x-modal`, `x-card`, `x-table`, `x-form`: reusable UI building blocks.
- Landing page: `resources/views/welcome.blade.php` uses a hero with brand gradient, animated medical SVGs, and feature badges.

## Internationalization (RTL/Arabic)
- Default locale set to Arabic (RTL).
- CSS rules prefer logical properties (`margin-inline-start`) and `[dir="rtl"]` blocks for directional tweaks.
- To switch languages, implement locale routes and use the language dropdown (see `layouts/guest.blade.php`).

## Accessibility
- Keyboard and screen-reader friendly components.
- Focus outlines via theme tokens; `prefers-reduced-motion` respected to limit animations.
- Semantic headings and ARIA attributes in components and layouts.

## Testing
- Run tests:
```bash
php artisan test
```
- Tests live under `tests/Feature` and `tests/Unit` with `tests/TestCase.php` bootstrap.

## Deployment
- Build production assets:
```bash
npm run build
```
- Configure web server to point to `public/` as document root.
- Optimize Laravel caches:
```bash
php artisan optimize:clear
php artisan optimize
```
- Ensure APP_ENV, APP_DEBUG, APP_URL, database, cache/queue config are set appropriately.

## Troubleshooting
- CSS/JS not updating:
  - Clear Vite/Laravel caches, hard refresh browser (Ctrl/Cmd+Shift+R).
  - Verify `@vite(['resources/css/app.css', ...])` includes the correct files.
- Black/incorrect backgrounds:
  - Confirm theme variables exist in `theme.css` and are loaded before page CSS.
  - Avoid undefined custom properties (use provided aliases like `--bg-color`, `--text-primary`).
- Mixed RTL/LTR spacing:
  - Prefer logical properties and check `[dir="rtl"]` overrides in component CSS.

## Common Tasks
- Create a new page with its own CSS/JS:
  1. Add `resources/views/yourpage.blade.php`.
  2. Add `resources/css/pages/yourpage.css` and `resources/js/pages/yourpage.js`.
  3. In the Blade head: `@vite(['resources/css/app.css','resources/css/pages/yourpage.css','resources/js/pages/yourpage.js'])`.
- Add a new component style:
  - Place it in `resources/css/components/yourcomponent.css` and import from your entry or page CSS.
- Update brand gradient globally:
  - Change `--brand-gradient-extended` in `resources/css/theme.css`.

## Contributing
- Follow PSR-12 for PHP and keep Blade components accessible and RTL-ready.
- Keep CSS modular; prefer tokens from `theme.css` and logical properties.
- Add tests where applicable and run `php artisan test` before pushing.

## License
This project is licensed. See the repository or your organization’s policy for details.
