# AI Onboarding Guide — Cinema Ticket System (Backend)

> Purpose: give an AI coding agent everything it needs to understand this repository before making changes.
> Read this file together with `AGENTS.md` (Boost-generated rules). `CLAUDE.md` is an identical copy of `AGENTS.md`.

## 1. What this project actually is

A **Laravel 13 backend** for a cinema ticket system. Important: despite the name, it is currently **almost a pristine Laravel skeleton**. There is no booking, showtime, seat, theater, payment, or API logic implemented yet.

The only domain foothold is a **Movie stub**:
- `database/migrations/2026_11_23_122709_create_movies_table.php` — creates `movies(id, created_at, updated_at)` only.
- `app/Models/Movie.php` — empty `Model` subclass (no `$fillable`, casts, factory, or relationships).
- `app/Http/Controllers/MovieController.php` — generated resource controller; **all seven methods are empty** (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`). Not wired to any route.

Treat any cinema feature request as **greenfield work built on this stub**, not as a modification of existing behavior.

## 2. Stack and versions (verified)

- PHP 8.5.1 locally; `composer.json` requires `php ^8.3`.
- `laravel/framework` 13.43.0 (constraint `^13.17`).
- Dev tools: PHPUnit 12.5, Pint 1.32, Mockery, Faker, Pail, Pao, Tinker, **Laravel Boost 2.10.4**.
- Frontend tooling present but unused for the API: Vite 8, Tailwind CSS 4, `laravel-vite-plugin` 3.1. `resources/js/app.js` is empty.
- No Sanctum/Passport, no Spatie packages, no API resource layer, no middleware, no services, no form requests.
- Confirm exact versions with `composer show --direct` before relying on package APIs.

## 3. Repository layout that matters

```
app/
  Models/User.php              Laravel 13 style: #[Fillable], #[Hidden], casts(), HasFactory<UserFactory>, Notifiable
  Models/Movie.php             EMPTY stub
  Http/Controllers/Controller.php   empty abstract base
  Http/Controllers/MovieController.php  EMPTY resource stub
  Providers/AppServiceProvider.php  empty register()/boot()
bootstrap/
  app.php                      routing + JSON exception config (see §5)
  providers.php                only AppServiceProvider
routes/
  web.php                      only GET / -> welcome view
  console.php                  default `inspire` command
  api.php                      DOES NOT EXIST (see §5)
database/
  migrations/                  3 Laravel defaults + movies migration
  factories/UserFactory.php    stock factory, `password` => 'password', `unverified()` state
  seeders/DatabaseSeeder.php   creates one test User (Test User / test@example.com)
tests/
  TestCase.php                 base test case
  Feature/ExampleTest.php      GET / returns 200
  Unit/ExampleTest.php         assertTrue(true)
config/                        unmodified Laravel 13 defaults
README.md                      stock Laravel README (no domain docs)
AGENTS.md / CLAUDE.md          Boost guidelines
boost.json, .mcp.json, opencode.json, .agents/skills/, .claude/skills/   Boost config (uncommitted)
```

`git log` has a single commit (`2fbe93b`, "Initial Commit") covering the skeleton; all Boost changes are uncommitted working-tree changes.

## 4. Database

- Default connection is **sqlite** (`.env.example`: `DB_CONNECTION=sqlite`, DB path `database/database.sqlite`). MySQL settings are commented out placeholders.
- Default cache/session/queue stores are `database`; tables exist via the stock migrations (`cache`, `cache_locks`, `sessions`, `jobs`, `job_batches`, `failed_jobs`, `password_reset_tokens`, `users`).
- `movies` has **no business columns yet** and there are **no foreign keys or pivot tables**.
- An empty root-level `db_cinema_ticket` file exists (likely an unused DB placeholder); it is not referenced by config.
- Tests use in-memory SQLite (`phpunit.xml`: `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`).

## 5. Routing and exceptions — critical detail

`bootstrap/app.php`:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

- There is **no `api:` argument**, so `routes/api.php` is not expected or loaded. `php artisan route:list --except-vendor` shows only `GET|HEAD /`.
- JSON error rendering is already configured: `$exceptions->shouldRenderJsonWhen(fn (Request $request) => $request->is('api/*') || $request->expectsJson())`.
- To introduce an API: **create `routes/api.php` AND add `api: __DIR__.'/../routes/api.php'` to `withRouting()`**. Use named routes and `apiResource`/`api` route conventions.
- Middleware block is empty — no custom middleware, aliases, groups, or CSRF exceptions.
- Health endpoint `/up` is framework-provided.

## 6. Conventions to follow

From `AGENTS.md` / Boost guidelines (treat as mandatory):

- Use `php artisan make:` commands for new classes; pass `--no-interaction`.
- Explicit return types and parameter type hints everywhere; curly braces even for single-line bodies.
- PHP 8 constructor property promotion; descriptive boolean names (`isRegisteredForDiscounts`).
- Prefer PHPDoc blocks over inline comments; use array-shape type definitions. TitleCase for enum keys.
- Laravel 13 style seen in `User.php`: `#[Fillable]` / `#[Hidden]` attributes and `casts()` method, rather than classic properties.
- Anonymous migration classes, as in the existing migrations.
- Create factories and seeders alongside new models; check existing factory states first.
- API work: default to Eloquent API Resources + API versioning, unless existing routes say otherwise (currently none exist).
- Use `route()` with named routes for links.
- Tests: PHPUnit feature tests (`php artisan make:test --phpunit Name`), extend `Tests\TestCase`, narrow runs with `php artisan test --compact --filter=...`.
- **After editing PHP: run `vendor/bin/pint --dirty --format agent`.**
- `.ai/rules/index.md` is referenced by `AGENTS.md` but the `.ai/` directory **does not exist** — ignore that reference.

## 7. Commands

```bash
composer install
cp .env.example .env          # .env already exists locally (git-ignored)
php artisan key:generate
php artisan migrate           # sqlite DB must exist
php artisan dev               # composer script "dev"
php artisan test --compact    # PHPUnit suite
vendor/bin/pint --dirty --format agent
php artisan route:list --except-vendor
php artisan boost:mcp         # MCP server used by configured AI agents
```

`composer setup` runs install, env copy, key generation, migrate, npm install and `npm run build`.

Environment note: Artisan commands print `PHP Warning: Module "mysqli" is already loaded` — a harmless local PHP config issue, not an application bug.

## 8. Suggested starting point for a feature

1. Add columns/relations for the feature in a **new migration** (don't rewrite the historical `movies` migration).
2. Fill `app/Models/Movie.php` (`$fillable` or attributes, casts, relations) and add a factory + seeder.
3. Implement `MovieController` (or new domain controllers) with form requests/resources as needed.
4. Create `routes/api.php`, register it in `bootstrap/app.php`, and define named API routes.
5. Add feature tests using in-memory SQLite and factories; run `php artisan test --compact`.
6. Run Pint on changed PHP files.
