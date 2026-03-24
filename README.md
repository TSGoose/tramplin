# Трамплин — карьерная платформа для вузов

Стартовый каркас проекта для конкурсной реализации.

## Стек
- Backend: Laravel 11 / PHP 8.3+ / PostgreSQL / Sanctum
- Frontend: Vue 3 / TypeScript / Vite / Pinia / Vue Router / Tailwind CSS
- Infra: Docker Compose / Nginx / PHP-FPM

## Структура
- `backend/` — Laravel API
- `frontend/` — SPA на Vue 3
- `docker/` — Docker-конфиги

## Важно
В этой среде Laravel не был сгенерирован автоматически, потому что недоступны Composer и внешний интернет.
Но структура, конфиги и стартовые файлы подготовлены так, чтобы ты мог:
1. локально создать/инициализировать Laravel-приложение в `backend/`;
2. скопировать подготовленные файлы поверх;
3. выполнить `composer install` и `npm install`;
4. продолжить разработку по блокам.

## Локальный запуск
### Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Frontend
```bash
cd frontend
npm install
npm run dev
```

### Docker
```bash
docker compose up --build
```

## Ближайшие блоки
1. Каркас
2. Auth + роли + users foundation
3. Публичный каталог возможностей
4. Профиль соискателя
5. Избранное и отклики
6. Работодатель
7. Куратор / админ
