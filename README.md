# event-booking-api

# Running with Docker (Newer Docker Versions)
## Build and Start Containers
```sh
docker compose up -d --build
```

## Initialse Env
```sh
cp .env.example .env
```

## Generate Application Key
Exec into containers
```exec
php artisan key:generate
```

## Run Migrations
Exec into containers
```sh
php artisan migrate
```
## Seed Database
Exec into containers
```sh
php artisan db:seed
```

## Stop Containers
```sh
docker compose down
```