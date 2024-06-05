# How to run?

1. [Install Docker](https://www.docker.com/)
2. `docker compose up -d`

**Config bap-connect-api:**
1. `cp .env.example .env`
2. `composer install` || `composer update`
3. `php artisan key:generate`
4. `php artisan jwt:secret` (override: yes)
5. Add environment variable to .env file (**Contact leader**)

**Config bap-connect-db:**
1. `cp .env.example .env`
2. Add environment variable to .env file (**Contact leader**)
3. `composer install`
4. `php artisan migrate`