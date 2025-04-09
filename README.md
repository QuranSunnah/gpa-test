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

### Run below corn cli to generate dashboard count/stats report data manually

```bash
php artisan cache:dashboard-stats
```

### Run below corn cli to generate dashboard report data manually

```bash
php artisan dashboard:generate-report (default yeasterday)
php artisan dashboard:generate-report --date="2025-02-27" (for specific day)
php artisan dashboard:generate-report --date="2025-02-27 2025-07-15" (date range)
```

### To run project in development mode

```bash
php artisan serve
```
