# AGENTS.md

## Project Overview

PHP-SaaS is a CLI scaffolding tool that generates production-ready Laravel 12 SaaS applications. It composes together backend, frontend, billing, testing, and project management stacks based on user-selected options.

- **CLI tool namespace:** `PHPSaaS\PHPSaaS`
- **Entry point:** `php-saas` (executable PHP script)
- **Commands:** `php-saas new <name>` (scaffold) and `php-saas dev` (development with file watching)

## Architecture

### CLI Tool (`src/`)

The CLI is built on Symfony Console with Laravel support packages. The core logic is organized into two commands and a set of composable traits:

- `NewCommand.php` -- Scaffolds a new project by orchestrating all setup traits
- `DevCommand.php` -- Development mode with Spatie Watcher for live file syncing
- `Support/helpers.php` -- Utility functions for file operations, renaming, and process helpers

**Traits** (each handles one dimension of scaffolding):

- `CollectInputs` -- Interactive prompts for stack selection
- `Backend` -- Copies the Laravel backend stack
- `Frontend` -- Copies React or Vue frontend stack into `resources/js/`
- `Billing` -- Configures Paddle, Stripe, or no billing
- `Tests` -- Sets up PHPUnit or Pest test suites
- `Projects` -- Handles Projects/Teams/Organizations renaming (files, directories, and content)
- `Tokens` -- Configures API token support via Sanctum
- `InteractWithBlocks` -- Processes `<php-saas:block>` conditional tags
- `RunCommands` -- Shell command execution helpers

### Template Stacks (`stacks/`)

These are the source templates that get composed into the generated application:

- `stacks/laravel/` -- Full Laravel 12 backend application (controllers, models, actions, migrations, routes, views, config)
- `stacks/react/` -- React + Inertia.js frontend (TypeScript, shadcn/ui components)
- `stacks/vue/` -- Vue 3 + Inertia.js frontend (TypeScript, reka-ui components)

### Block Tag System

Template files use conditional `<php-saas:block>` tags to mark code sections that should be included or removed based on user choices. Supported comment formats:

- PHP: `// <php-saas:billing>` ... `// </php-saas:billing>`
- JSX/TSX: `{/*<php-saas:billing>*/}` ... `{/*</php-saas:billing>*/}`
- HTML: `<!--<php-saas:billing>-->` ... `<!--</php-saas:billing>-->`
- .env: `#<php-saas:billing>` ... `#</php-saas:billing>`

Block names include: `billing`, `projects`, `teams`, `organizations`, `tokens`, `vite`.

## Code Style and Conventions

### PHP

- **Laravel Pint** with the `laravel` preset for formatting
- CLI source uses `pint.json` at the root (excludes `stacks/`)
- Generated app has its own `pint.json` in `stacks/laravel/`
- **Larastan** (PHPStan) at level 5 for static analysis
- **Rector** for automated refactoring (deadCode, codeQuality, typeDeclarations, privatization, earlyReturn, strictBooleans)
- PSR-4 autoloading under `PHPSaaS\PHPSaaS\` namespace
- 4-space indentation for PHP files

### JavaScript/TypeScript

- **ESLint 9** with framework-specific plugins (React or Vue)
- **Prettier** with `prettier-plugin-tailwindcss` and `prettier-plugin-organize-imports`
- Semicolons enabled, single quotes, 150 print width, 2-space indentation
- TypeScript strict mode

### General

- UTF-8, LF line endings
- 2-space indent default (4 for PHP and Blade)
- Trim trailing whitespace

### Architecture Patterns (Generated App)

- **Actions** for business logic (e.g., `CreateProject`, `DeleteUser`) -- not service classes
- **DTOs** via Spatie Laravel Data
- **Enums** for constants (e.g., `ProjectRole`)
- **Traits** for shared model behavior (e.g., `HasProjects`)
- **Policies** for authorization
- **API Resources** for JSON serialization
- **Service Providers** for module bootstrapping (`AuthServiceProvider`, `ProjectServiceProvider`, `BillingServiceProvider`)

## Testing

- The CLI tool itself has no unit tests; it is tested via integration in CI
- The generated app supports **PHPUnit** (`tests-phpunit/`) and **Pest** (`tests-pest/`)
- Tests run against SQLite in-memory (`:memory:`)
- Feature tests cover: Auth, Billing (Paddle/Stripe), Home, Profile, Projects, Dashboard, Tokens

### Running CLI Tests Locally

```bash
# Scaffold a test project with all options
php php-saas new example --backend laravel --frontend react --test pest --projects projects --billing paddle --tokens yes --npm yes

# Run the generated app's tests
cd example && php artisan test
```

## CI/CD

### CLI Repository Workflows (`.github/workflows/`)

- `tests.yml` -- Matrix test across all stack combinations (2 OSes x 2 frontends x 2 test frameworks x 4 project names x 2 billing providers x 2 token options)
- `code-style.yml` -- Runs `pint --test` on CLI source

### Generated App Workflows (`stacks/laravel/.github/workflows/`)

- `tests.yml` -- PHP 8.4, Node 22, SQLite, runs `php artisan test`
- `code-style.yml` -- Runs `pint --test --parallel` and `npm run lint`

## Development Workflow

Use the `dev` command during development to auto-sync stack changes into a `dist/` directory:

```bash
# Install dev dependencies (includes Spatie Watcher)
composer install
npm install

# Start dev mode (watches stacks/ for changes)
php php-saas dev --frontend react --billing paddle --test pest --projects projects --tokens yes
```

This scaffolds into `dist/` and watches `stacks/` for file changes, copying updates in real-time.

## Key Files Reference

| File | Purpose |
|---|---|
| `php-saas` | CLI entry point |
| `src/NewCommand.php` | Main scaffolding command |
| `src/DevCommand.php` | Dev mode with file watching |
| `src/Traits/InteractWithBlocks.php` | Block tag processor |
| `src/Support/helpers.php` | Shared utility functions |
| `stacks/laravel/` | Laravel backend template |
| `stacks/react/` | React frontend template |
| `stacks/vue/` | Vue frontend template |
| `pint.json` | CLI code style config |
| `stacks/laravel/pint.json` | Generated app code style config |
| `stacks/laravel/phpstan.neon` | Static analysis config |
| `stacks/laravel/rector.php` | Rector refactoring config |

## Git

- Use `gh` tool to get issues and pull requests.
