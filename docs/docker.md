# Docker

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

If you need to change the project path, update `APP_HOST_PATH` in `docker/.env`.
