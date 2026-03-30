#!/usr/bin/env bash
set -e

echo "==> Build and start containers"
docker compose -f docker-compose.prod.yml up -d --build

echo "==> Install backend dependencies"
docker compose -f docker-compose.prod.yml exec app composer install --optimize-autoloader

echo "==> Ensure app key"
docker compose -f docker-compose.prod.yml exec app php artisan key:generate --force || true

echo "==> Migrate database"
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force

echo "==> Seed demo data"
docker compose -f docker-compose.prod.yml exec app php artisan db:seed --force

echo "==> Storage link"
docker compose -f docker-compose.prod.yml exec app php artisan storage:link || true

echo "==> Clear and cache config"
docker compose -f docker-compose.prod.yml exec app php artisan optimize:clear
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache

echo "==> Health check"
curl -f http://localhost/api/health || true

echo "Done"
