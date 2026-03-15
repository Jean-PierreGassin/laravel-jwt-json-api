# Quickstart

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan serve
```

Update `.env` with your database credentials before running `migrate`.
