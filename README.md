# TabDebt

A bookmark manager built with Laravel. Organize websites into groups, tag them with color-coded labels, and manage everything from a personal workspace dashboard.

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
  - [Docker with Laravel Sail](#option-a-docker-with-laravel-sail-recommended)
  - [Laravel Herd](#option-b-laravel-herd)
- [Database](#database)
  - [Commands](#database-commands)
  - [Seed Data](#seed-data)
- [Development](#development)
- [Routes](#routes)
- [Project Structure](#project-structure)
- [Troubleshooting](#troubleshooting)
- [License](#license)

## Features

- **Websites** — Save bookmarks with name, URL, description, and quality rating
- **Groups** — Organize websites into collections (default "Other" group created per user)
- **Tags** — Color-coded labels attachable to both websites and groups
- **Workspace** — Dashboard with stats, recent websites, groups, and tags
- **Auth** — Register, login, email verification, password reset (Laravel Breeze)
- **Dark Mode** — Tailwind CSS dark theme

## Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Language | PHP | 8.5 |
| Framework | Laravel | 12 |
| Frontend | Tailwind CSS | 3 |
| Reactivity | Livewire + Volt | 3 / 1 |
| Database | MySQL | 8.4 |
| Build | Vite | 7 |
| Testing | Pest | 4 |
| Code Style | Laravel Pint | 1 |
| Containers | Laravel Sail | 1 |

## Prerequisites

- **Docker Desktop** — [docker.com](https://www.docker.com/products/docker-desktop/) *or* **Laravel Herd** — [herd.laravel.com](https://herd.laravel.com/)
- **Composer** — PHP dependency manager
- **Node.js** (v22+) & **npm**
- **Git**

## Installation

### Option A: Docker with Laravel Sail (Recommended)

```bash
git clone https://github.com/Palmkonde/tabdebt.git && cd tabdebt

# Install PHP deps via Docker (no local PHP needed)
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html \
  laravelsail/php85-composer:latest composer install --ignore-platform-reqs

cp .env.example .env
```

Update `.env` — set `DB_HOST=mysql` (the Docker service name):

```dotenv
APP_NAME=TabDebt
DB_HOST=mysql
DB_DATABASE=tabdebt
DB_USERNAME=sail
DB_PASSWORD=password
```

```bash
vendor/bin/sail up -d
vendor/bin/sail artisan key:generate
vendor/bin/sail npm install && vendor/bin/sail npm run build
vendor/bin/sail artisan migrate
vendor/bin/sail artisan db:seed          # optional: sample data
```

Visit [http://localhost](http://localhost).

### Option B: Laravel Herd

```bash
git clone https://github.com/Palmkonde/tabdebt.git && cd tabdebt
composer install && npm install
cp .env.example .env
```

Update `.env` with your local MySQL credentials:

```dotenv
APP_NAME=TabDebt
APP_URL=http://tabdebt.test
DB_HOST=127.0.0.1
DB_DATABASE=tabdebt
DB_USERNAME=root
DB_PASSWORD=
```

Create a `tabdebt` database in MySQL, then:

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed                      # optional
npm run build
```

Link the site in Herd and visit `http://tabdebt.test`.

## Database

> If using Herd, replace `vendor/bin/sail artisan` with `php artisan`.

### Database Commands

| Command | Description |
|---------|-------------|
| `vendor/bin/sail artisan migrate` | Run pending migrations |
| `vendor/bin/sail artisan migrate:rollback` | Rollback last batch |
| `vendor/bin/sail artisan migrate:fresh` | Drop all tables and re-migrate |
| `vendor/bin/sail artisan migrate:fresh --seed` | Fresh migrate + seed |
| `vendor/bin/sail artisan db:seed` | Seed the database |

### Seed Data

- **Test user** — `palm@example.com` / password: `password`
- 20 random color-coded tags
- 3 groups with 5 websites each (for test user)
- 10 additional users with their own groups and websites

## Development

```bash
vendor/bin/sail up -d                          # start containers
vendor/bin/sail composer run dev               # full dev server (app + queue + Vite)
# or just Vite hot-reload:
vendor/bin/sail npm run dev
```

```bash
vendor/bin/sail bin pint                       # fix code style
vendor/bin/sail bin pint --test                # check code style (dry run)
vendor/bin/sail artisan test --compact         # run tests
vendor/bin/sail artisan test --filter=TestName # run specific test
vendor/bin/sail stop                           # stop containers
```

## Routes

### Public

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Landing page |

### Authenticated

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/workspace` | Dashboard |
| GET/POST | `/websites` | List / Store |
| GET | `/websites/create` | Create form |
| GET/PUT/DELETE | `/websites/{id}/edit` | Edit / Update / Delete |
| GET/POST | `/groups` | List / Store |
| GET | `/groups/create` | Create form |
| GET/PUT/DELETE | `/groups/{id}/edit` | Edit / Update / Delete |
| DELETE | `/groups/{id}/websites/{id}` | Remove website from group |
| GET/POST | `/tags` | List / Store |
| GET | `/tags/create` | Create form |
| GET | `/tags/{id}` | Tag detail (websites + groups) |
| GET/PUT/DELETE | `/tags/{id}/edit` | Edit / Update / Delete |

### Auth (Breeze)

`/register`, `/login`, `/forgot-password`, `/reset-password/{token}`, `/verify-email`, `/confirm-password`

## Project Structure

```
app/
├── Http/Controllers/       # Route controllers
├── Models/                 # User, Group, Website, Tag
└── View/Components/        # Blade component classes
database/
├── factories/              # Model factories
├── migrations/             # Schema migrations
└── seeders/                # Database seeders
resources/views/
├── components/             # Reusable Blade components
├── groups/                 # Group CRUD views
├── websites/               # Website CRUD views
├── tags/                   # Tag views
├── workspace/              # Dashboard
└── livewire/               # Volt components (auth, profile)
routes/
├── web.php                 # App routes
└── auth.php                # Auth routes
tests/
├── Feature/                # Feature tests
└── Unit/                   # Unit tests
```

## Troubleshooting

| Problem | Solution |
|---------|----------|
| Port 80 in use | Set `APP_PORT=8080` in `.env`, access at `localhost:8080` |
| DB connection refused (Sail) | Set `DB_HOST=mysql` in `.env`, not `127.0.0.1` |
| DB connection refused (Herd) | Ensure MySQL is running, use `DB_HOST=127.0.0.1` |
| MySQL access denied | Ensure `.env` credentials match `compose.yaml`. Reset with `sail down -v && sail up -d && sail artisan migrate --seed` |
| Vite manifest not found | Run `vendor/bin/sail npm run build` or `vendor/bin/sail npm run dev` |
| Test DB missing | Run `vendor/bin/sail mysql -e "CREATE DATABASE IF NOT EXISTS testing;"` |

## License

Open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
