# Laravel JWT + JSON API

Lightweight Laravel API example with JWT authentication and JSON:API-style responses. This repository is a focused proof of concept intended for learning and experimentation.

## Table of Contents
- Overview: [docs/overview.md](docs/overview.md)
- Requirements: [docs/requirements.md](docs/requirements.md)
- Quickstart: [docs/quickstart.md](docs/quickstart.md)
- Docker: [docs/docker.md](docs/docker.md)
- API: [docs/api.md](docs/api.md)
- Status: [docs/status.md](docs/status.md)

## What This Includes
- Laravel 12 API scaffolding with JWT-based auth
- JSON:API-style payloads for success and error responses
- Minimal endpoints for registration, login, and current-user lookup
Full details: [docs/overview.md](docs/overview.md).

## Requirements
- PHP 8.2+
- Composer
- A database (MySQL by default)
Full details: [docs/requirements.md](docs/requirements.md).

## Quickstart
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan serve
```

Update `.env` with your database credentials before running `migrate`.
Full details: [docs/quickstart.md](docs/quickstart.md).

## Docker (Optional)
The `docker/` folder provides an Nginx + PHP-FPM + MySQL + Redis + Node + MailHog stack.

```bash
cd docker
cp .env.example .env
docker compose up -d --build
docker compose exec php composer install
docker compose exec php php artisan key:generate
docker compose exec php php artisan jwt:secret
docker compose exec php php artisan migrate
```

Update the application `.env` (project root) to match the Docker services:
- `DB_HOST=mysql`
- `DB_PORT=3306`
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` should match `docker/.env`

App URL: `http://localhost:8080` (or your `APP_HTTP_PORT`).
Full details: [docs/docker.md](docs/docker.md).

## API
Base URL: `http://localhost:8000/api`

Register:
```bash
curl -X POST http://localhost:8000/api/user/register \
  -H "Content-Type: application/json" \
  -d '{"data":{"type":"user","attributes":{"name":"Jane","email":"jane@example.com","password":"secret123"}}}'
```

Login:
```bash
curl -X POST http://localhost:8000/api/user/login \
  -H "Content-Type: application/json" \
  -d '{"data":{"type":"user","attributes":{"email":"jane@example.com","password":"secret123"}}}'
```

Response includes a JWT:
```json
{
  "data": [
    {
      "type": "authtoken",
      "attributes": {
        "token": "..."
      }
    }
  ]
}
```

Current user:
```bash
curl http://localhost:8000/api/me \
  -H "Authorization: Bearer <token>"
```
Full details: [docs/api.md](docs/api.md).

## Status
This project is not intended for production use.
Full details: [docs/status.md](docs/status.md).
