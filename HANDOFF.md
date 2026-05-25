# Laragigs2 — Project Handoff Document (Updated)

## Context

The user is rebuilding the **Laragigs** project (originally a follow-along YouTube course built in Laravel 9.x) from scratch in **Laravel 13.11.2**, using it as a vehicle to get back up to speed with Laravel after a 3–4 year gap spent working in Vue/Vuetify. The user has strong prior experience with Laravel (6.x through 9.x), PHP, and deep database expertise including extensive DB2 experience (formerly taught DB2 for IBM).

The approach is: follow the original Laragigs course video independently, only asking for help when hitting version differences or unclear concepts. The user learns by doing, not by watching.

A second goal has emerged: **contributing to the FakerPHP open source project**, specifically by improving Canadian locale support and documenting existing undocumented formatters.

---

## Development Environment

| Component | Details |
|---|---|
| OS | Windows 11 |
| PHP | 8.4.20 (via Herd) / 8.4.13 (CLI at C:\Program Files\php) |
| Laravel version | 13.11.2 |
| Site serving | Laravel Herd (free tier) — Nginx |
| Database | MySQL 9.7 Community Server (installed as Windows service, auto-start) |
| DB GUI | DBeaver Community 26.x |
| Editor | VSCode |
| DevGigs location | `C:\Laravel\devgigs` |
| MyStuff location | `C:\Laravel\mystuff` |
| DevGigs local URL | `http://devgigs.test` |
| MyStuff local URL | `http://mystuff.test` |

### VSCode Extensions Installed
- Laravel Extra Intellisense
- Auto Close Tag
- PHP by DEVSENSE (installed but may be disabled)
- PHP Intelephense (installed)
- i18n-ally (configured to use `lang` folder)
- Laravel Blade Formatter (installed and active)
- Vue-related extensions (Volar etc.) from prior Vue/Vuetify work

### VSCode settings.json notes
- Quick suggestions suppressed (`"other": "off"`) — use **Ctrl+Space** to trigger manually
- `emmet.includeLanguages`: `{ "blade": "html" }`
- `emmet.triggerExpansionOnTab`: `true`
- **Shift+Alt+F** formats current document
- Parameter hints not working — Devsense premium feature

### Known Intelephense False Positives
- `auth()->login()` and `auth()->logout()` flagged as undefined — works fine
- `$this->propertyName` inside Pest `beforeEach()` and `it()` closures flagged as undefined — works fine. Intelephense doesn't understand Pest's shared `$this` context

---

## Project Structure

The original Laragigs2 project has been split into two separate applications:

### DevGigs (`C:\Laravel\devgigs`)
- **Purpose:** IT job board — portfolio piece
- **Local URL:** `http://devgigs.test`
- **Production URL:** `https://devgigs.onrender.com`
- **GitHub:** `https://github.com/RhinoCan/devgigs` (public)
- **Content:** Gigs section only. Full CRUD including auth-protected create/edit/delete.
- **Routes:** No `/gigs` prefix — routes are at `/`, `/{gig}`, `/{gig}/edit` etc.
- **`/` route:** Gigs index

### MyStuff (`C:\Laravel\mystuff`)
- **Purpose:** Personal catalogue of books and vinyl LPs
- **Local URL:** `http://mystuff.test`
- **Production URL:** `https://mystuff-940q.onrender.com`
- **GitHub:** `https://github.com/RhinoCan/mystuff` (private)
- **Content:** Books and Albums sections. Full CRUD including auth-protected create/edit/delete.
- **Routes:** `/books/*` and `/albums/*` prefixes retained
- **`/` route:** Menu view letting user choose between Books and Albums

---

## Database

### Local
- **Engine:** MySQL 9.7
- **DevGigs DB:** `devgigs`
- **MyStuff DB:** `mystuff`
- **Test DBs:** `devgigs_test` and `mystuff_test`
- **Charset:** `utf8mb4` / **Collation:** `utf8mb4_unicode_ci`
- **User:** `root`

### Production
- **Engine:** PostgreSQL (via Neon — free permanent tier)
- **DevGigs:** Neon project `DevGigs`, database `neondb`
- **MyStuff:** Neon project `MyStuff`, database `neondb`
- Migrations are database-agnostic — no MySQL-specific syntax used

### .env database config (local)
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=devgigs  (or mystuff)
DB_USERNAME=root
DB_PASSWORD=<MySQL root password>
APP_URL=http://devgigs.test  (or http://mystuff.test)
```

### Tables
| App | Model | Table | Notes |
|---|---|---|---|
| DevGigs | `Gig` | `gigs` | IT job listings |
| DevGigs | `User` | `users` | Auth |
| MyStuff | `Book` | `books` | Book catalogue |
| MyStuff | `Album` | `albums` | Vinyl LP catalogue |
| MyStuff | `User` | `users` | Auth |

---

## Hosting & Deployment

### Stack
- **App hosting:** Render (free tier web service)
- **Database:** Neon (free permanent PostgreSQL)
- **Image storage:** Cloudinary (free tier)

### Render free tier behaviour
- Services spin down after 15 minutes of inactivity
- First request after spin-down takes ~1 minute to wake up
- During spin-up, redirects may land on unexpected pages — wait a minute or two after cold start before testing
- No persistent filesystem — uploaded files cannot be stored on the server

### Docker configuration
Each project has these files for Render deployment:
- `Dockerfile` — uses `php:8.4-fpm-alpine` base image
- `docker/nginx.conf` — nginx configuration with 10MB upload limit and 300s timeouts
- `docker/supervisord.conf` — manages nginx and php-fpm processes
- `scripts/00-laravel-deploy.sh` — runs on each deploy: package discovery, config/route cache, migrations, storage setup

### Deploy script (`scripts/00-laravel-deploy.sh`)
```sh
#!/usr/bin/env sh

echo "Running package discovery..."
php artisan package:discover --ansi

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Creating storage directories..."
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/app/public/logos  (devgigs) or covers (mystuff)
chmod -R 775 /var/www/html/storage

echo "Linking storage..."
php artisan storage:link

echo "Setting public storage permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/public
chmod -R 775 /var/www/html/public/storage
```

### Render environment variables (both projects)
```
APP_NAME=DevGigs  (or MyStuff)
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generated key>
APP_URL=<render URL>
DB_CONNECTION=pgsql
DB_HOST=<neon host>
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=<neon password>
CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
```

---

## Image Storage (Cloudinary)

### Architecture
Images are uploaded **directly from the browser to Cloudinary** using an unsigned upload preset. The server never handles the image file. The Cloudinary URL is returned to the browser via JavaScript, stored in a hidden form field, and submitted to Laravel which saves the URL to the database.

This approach was chosen because Render's free tier has an ephemeral filesystem and insufficient CPU for server-side uploads.

### Cloudinary account
- **Cloud name:** `daufnw5dc`
- **Upload preset:** `devgigs_upload` (unsigned)
- **Folder structure:**
  - `devgigs/logos/` — DevGigs gig logos
  - `mystuff/covers/books/` — MyStuff book covers
  - `mystuff/covers/albums/` — MyStuff album covers

### Cloudinary PHP SDK
- Package: `cloudinary/cloudinary_php` (installed in both projects)
- Used only for **deleting** images when a record is deleted
- `CLOUDINARY_URL` environment variable used for authentication

### Controller pattern

**store() — saving a URL from browser upload:**
```php
if ($request->filled('logo_url')) {
    $formFields['logo'] = $request->input('logo_url');
}
```

**update() — replacing or removing:**
```php
if ($request->filled('logo_url')) {
    $formFields['logo'] = $request->input('logo_url');
} elseif ($request->input('remove_logo') === '1') {
    $formFields['logo'] = null;
}
```

**destroy() — deleting from Cloudinary:**
```php
if ($gig->logo) {
    try {
        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
        $publicId = 'devgigs/logos/' . pathinfo(parse_url($gig->logo, PHP_URL_PATH), PATHINFO_FILENAME);
        $cloudinary->uploadApi()->destroy($publicId);
    } catch (\Exception $e) {
        // Don't block deletion if Cloudinary fails
    }
}
```

### Blade pattern (direct browser upload)
```javascript
async handleFile(event) {
    const file = event.target.files[0];
    if (!file) return;
    this.uploading = true;
    const formData = new FormData();
    formData.append('file', file);
    formData.append('upload_preset', 'devgigs_upload');
    formData.append('folder', 'devgigs/logos');
    const response = await fetch('https://api.cloudinary.com/v1_1/daufnw5dc/image/upload', {
        method: 'POST',
        body: formData
    });
    const data = await response.json();
    this.previewUrl = data.secure_url;
    this.$refs.logoUrl.value = data.secure_url;
    this.uploading = false;
}
```

---

## Routing

### DevGigs (`web.php`)
No `/gigs` prefix. Static routes defined before `{gig}` wildcard. All `{gig}` wildcard routes use `.where('gig', '[0-9]+')` to prevent swallowing named routes like `/login` and `/register`.

```php
Route::get('/', [GigController::class, 'index']);
Route::get('/create', [GigController::class, 'create'])->middleware('auth');
Route::get('/manage', [GigController::class, 'manage'])->middleware('auth');
Route::post('/', [GigController::class, 'store'])->middleware('auth');
Route::get('/{gig}', [GigController::class, 'show'])->where('gig', '[0-9]+');
Route::get('/{gig}/edit', [GigController::class, 'edit'])->middleware('auth')->where('gig', '[0-9]+');
Route::put('/{gig}', [GigController::class, 'update'])->middleware('auth')->where('gig', '[0-9]+');
Route::delete('/{gig}', [GigController::class, 'destroy'])->middleware('auth')->where('gig', '[0-9]+');
Route::get('/{gig}/delete-confirm', [GigController::class, 'confirmDelete'])->middleware('auth')->where('gig', '[0-9]+');
```

### MyStuff (`web.php`)
```php
Route::get('/', function () { return view('menu'); });
// Books and Albums routes with /books/* and /albums/* prefixes
```

---

## Navigation Source Tracking (edit/delete redirects)

After editing or deleting a record, the app redirects back to the appropriate page based on where the user came from:
- From **Manage page** → redirect back to Manage
- From **Show page** → redirect back to Index

### Implementation
Links from manage pages pass `?source=manage`, links from show pages pass `?source=show`. The edit form and delete-confirm form carry this through as a hidden field. Controllers use it for the redirect:

```php
$source = $request->input('source');
return redirect($source === 'manage' ? '/manage' : '/')->with('message', '...');
```

MyStuff uses `/books/manage` vs `/books` and `/albums/manage` vs `/albums` respectively.

---

## Controllers

### DevGigs: `GigController`
Methods: `index`, `show`, `create`, `store`, `edit`, `update`, `destroy`, `manage`, `confirmDelete`
- `update()` and `destroy()` accept `Request $request` as first parameter (needed for source tracking)
- Ownership check (403) in `edit`, `update`, `destroy`, `confirmDelete`

### MyStuff: `BookController`, `AlbumController`
Same method set as GigController. Field is `cover` instead of `logo`.

### Both apps: `UserController`
Handles register/login/logout/authenticate. Identical in both projects.

---

## App Structure

### DevGigs views
```
resources/views/
  layout.blade.php
  gigs/
    index.blade.php
    show.blade.php
    create.blade.php
    edit.blade.php
    manage.blade.php
    delete-confirm.blade.php
  users/
    register.blade.php
    login.blade.php
  partials/
    _hero_gigs.blade.php
    _search_gigs.blade.php
  components/
    card.blade.php
    gig-tags.blade.php
    gig-card.blade.php
    flash-message.blade.php
    layout.blade.php
    footer.blade.php
```

### MyStuff views
```
resources/views/
  layout.blade.php
  menu.blade.php
  books/
    index.blade.php
    show.blade.php
    create.blade.php
    edit.blade.php
    manage.blade.php
    delete-confirm.blade.php
  albums/
    (same structure as books)
  users/
    register.blade.php
    login.blade.php
  partials/
    _hero_books.blade.php
    _hero_albums.blade.php
    _search_books.blade.php
    _search_albums.blade.php
  components/
    card.blade.php
    flash-message.blade.php
    layout.blade.php
    footer.blade.php
```

---

## Components

- `<x-layout>` — main layout wrapper; accepts `$footer` named slot
- `<x-card>` — styled card wrapper
- `<x-gig-tags>` — displays comma-separated tags as styled pills (DevGigs only)
- `<x-gig-card>` — gig summary card (DevGigs only)
- `<x-flash-message>` — displays session flash messages
- `<x-footer>` — footer component with props: `bgColor`, `buttonText`, `buttonHref`, `showButton`

---

## Authentication

Standard Laravel auth. Users own records (`user_id` foreign key, included in `$fillable`). Manage page and all mutating routes protected with `->middleware('auth')`. Edit and confirmDelete methods enforce ownership with 403 abort. Hand-built views (not Breeze).

---

## Faker / Seeder

### DevGigs
- `App\Faker\DevGigsProvider` — provides `techTitle()` and `techSkills()`/`techSkillsCsv()`
- `App\Faker\CanadaDataProvider` — provides Canadian cities and `areaCode()`
- Both registered in `AppServiceProvider::boot()` wrapped in `if ($this->app->environment('local', 'testing'))` to prevent production errors (Faker is a dev dependency)
- `GigFactory` uses `techTitle()` and `techSkillsCsv()`

### MyStuff
- No custom Faker providers
- `BookFactory` uses `$this->faker->words(3, true)` for title and `$this->faker->name()` for author
- `AlbumFactory` uses `$this->faker->words(3, true)` for title and `$this->faker->name()` for artist

---

## Testing

### Setup
- **Framework:** Pest
- **Test databases:** `devgigs_test` and `mystuff_test` (MySQL)
- **`.env.testing`** in each project root
- **`phpunit.xml`** — DB_CONNECTION set to mysql, DB_DATABASE set to test database
- Xdebug installed for CLI PHP for coverage reports

### Running tests
```bash
php artisan test
php artisan test --coverage
php artisan test --coverage --coverage-html=coverage-report
```

### Current test files
| App | File | Coverage |
|---|---|---|
| DevGigs | `tests/Feature/GigTest.php` | ~83% GigController |
| MyStuff | `tests/Feature/BookTest.php` | ~80% BookController |
| MyStuff | `tests/Feature/AlbumTest.php` | ~80% AlbumController |

### Known coverage gaps (priority for next session)
- Cloudinary upload/remove branches in `store()` and `update()` — requires mocking Cloudinary SDK
- Tag filter on gigs index
- Search filter on all index pages
- Manage page only shows current user's own records
- PUT and DELETE ownership enforcement (403 for non-owners)
- Source-based redirect behaviour in `update()` and `destroy()`

---

## filesystems.php

Both projects have this setting changed from default:
```php
'default' => env('FILESYSTEM_DISK', 'local'),
```

---

## Artisan Commands Reference

```bash
php artisan migrate
php artisan migrate:rollback
php artisan migrate:refresh --seed
php artisan make:model ModelName -m
php artisan make:controller ControllerName
php artisan make:migration description
php artisan db:seed
php artisan route:list
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan storage:link
php artisan tinker
php artisan test
php artisan test --coverage
```

---

## Common Gotchas

- `APP_URL` in `.env` must match the local Herd URL
- `npm run dev` must be running after Windows reboots
- MySQL service auto-starts but verify with `net start MySQL97` if site is down
- `->nullable` without `()` silently does nothing — always `->nullable()`
- Missing `use` statement for models causes class not found errors
- Two PHP installations: Herd (8.4.20) for web, `C:\Program Files\php` (8.4.13) for CLI
- Factory fields must include all non-nullable columns
- Email addresses in Pest test data: concatenate the `@` to prevent Intelephense misreading
- Single-quoted strings in PHP do NOT interpolate variables
- Faker providers wrapped in `environment('local', 'testing')` check in AppServiceProvider — required because Faker is a dev dependency and not available in production
- Render free tier cold starts — give 1-2 minutes after spin-up before testing, first requests may land on unexpected pages
- Cloudinary API secret is masked in the dashboard — click "Show password" to reveal the real value before copying the `CLOUDINARY_URL`
- DevGigs `{gig}` wildcard routes require `.where('gig', '[0-9]+')` constraint — without it, named routes like `/login` are swallowed by the wildcard
- `destroy()` and `update()` methods must accept `Request $request` as first parameter for source tracking to work

---

## Laravel 9 → 13 Differences Encountered

- `RouteServiceProvider.php` does not exist in Laravel 13
- `auth()->login()` and `auth()->logout()` flagged by Intelephense as undefined — false positives
- `app/Http/Middleware/` directory does not exist in Laravel 13
- Relationship `->gigs` returns Collection directly; `->get()` not needed
- Service providers registered in `bootstrap/providers.php` not `config/app.php`
- No PHP runtime available in Docker during build — deploy script runs at container startup not build time

---

## FakerPHP Contribution — Status

### Repositories
- `FakerPHP/Faker` — forked and cloned to `C:\Laravel\Faker`
- `FakerPHP/fakerphp.github.io` — forked and cloned to `C:\Laravel\fakerphp.github.io`

### Pull Requests Submitted (awaiting review)
| PR | Repo | Description |
|---|---|---|
| #120 | fakerphp.github.io | Add Company formatter documentation |
| #121 | fakerphp.github.io | Add en_CA locale documentation |
| #1056 | FakerPHP/Faker | Add en_CA Person provider with sin() method |

---

## Side Project — VSCode Extension

A planning document for a "Numbered Cat" VSCode extension has been written and saved separately (`vscode-numbered-cat-extension.md`). The extension adds a right-click context menu item in the file explorer that produces a copy of the selected file with line numbers prepended — useful for sharing code with AIs that reference line numbers.