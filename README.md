## GP Academy API

### Required php 8.2 or higher

Create a .env file at the project root and copy content from .env.example file and provide necessary value on it.

```bash
cp .env.example .env
```

### Run cli

```bash
npm install
npm install --global yarn
composer install
php artisan migrate:fresh --seed
php artisan passport:install (first time)
php artisan passport:client --personal (first time)
```

### Run below cli for redis queue (These are need to run using supervisor in production)

```bash
php artisan horizon (if horizon is installed, it will run -> php artisan queue:work)
or
php artisan queue:work
php artisan schedule:work
```

### To run project in development mode

```bash
php artisan serve
```
