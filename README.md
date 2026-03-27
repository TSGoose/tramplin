# Tramplin

**Карьерная цифровая платформа для современного ВУЗа.**

---

## О проекте

Tramplin — это многоуровневая веб-платформа, которая объединяет студентов, работодателей, кураторов и администраторов в единой цифровой среде ВУЗа.

Проект создается как конкурсное решение и ориентирован на демонстрацию полноценного продуктового подхода:
- современный интерфейс;
- понятные пользовательские сценарии;
- модульная архитектура;
- ролевая модель доступа;
- рабочий backend API;
- реалистичный контур модерации и проверки качества контента.

Платформа покрывает ключевые карьерные процессы:
- студент создает профиль и ищет возможности;
- работодатель создает и публикует карьерные предложения;
- куратор модерирует компании и карточки возможностей;
- администратор управляет пользователями и создает кураторов.

---

## Основные возможности

### Для студентов
- регистрация и вход;
- личный карьерный профиль;
- поиск карьерных возможностей;
- фильтрация каталога;
- карта возможностей;
- избранное;
- отклики с сопроводительным письмом;
- история откликов.

### Для работодателей
- регистрация и вход;
- профиль компании;
- отправка компании на верификацию;
- создание карьерных возможностей;
- редактирование карточек;
- отправка карточек на модерацию;
- получение замечаний от куратора;
- повторная отправка после исправлений.

### Для кураторов
- отдельный контур входа;
- список компаний на верификации;
- список возможностей на модерации;
- одобрение;
- отклонение;
- отправка на доработку;
- журнал действий.

### Для администраторов
- отдельный контур входа;
- просмотр пользователей;
- фильтрация по ролям;
- создание новых кураторов.

---

## Роли пользователей

В системе используются следующие роли:
- `applicant` — студент / соискатель;
- `employer` — работодатель;
- `curator` — модератор / куратор;
- `admin` — администратор.

---

## Пользовательские контуры входа

### Обычный вход
Используется для:
- `applicant`
- `employer`

Маршрут frontend:
```
/login
```
Backend endpoint:
```
POST /api/auth/login
```

### Контур куратора
Используется для:
- `curator`
- `admin`

Маршрут frontend:
```
/login/curator
```
Backend endpoint:
```
POST /api/auth/login-curator
```

---

## Технологический стек

### Backend
- Laravel 11
- PHP 8.4 / 8.5
- PostgreSQL
- Laravel Sanctum
- PHPUnit / Pest-совместимый тестовый контур
- строгая типизация (`declare(strict_types=1)`)

### Frontend
- Vue 3
- TypeScript
- Pinia
- Vue Router
- Axios
- Tailwind CSS
- Zod

### Инфраструктура
- Docker
- Laravel Sail
- Yandex Maps API (интеграция начата, требует корректного API key для финального demo)

---

## Архитектура

Проект построен по модульному принципу.

### Backend
Ключевые элементы backend-архитектуры:
- Enum-классы для доменных статусов;
- Form Request для валидации;
- API Resources для нормализованных ответов;
- небольшие domain/service-классы;
- feature tests на функциональные блоки;
- role-based business logic.

### Frontend
Структура frontend строится вокруг:
- `entities` — доменные типы;
- `features` — API и store-логика;
- `pages` — страницы;
- `widgets` — композиционные UI-блоки;
- `shared` — базовые UI-компоненты и util-слой.

---

## Структура проекта

### Backend
```
app/Enums            — enum-статусы домена
app/Http/Controllers — API-контроллеры
app/Http/Requests    — валидация запросов
app/Http/Resources   — JSON-ресурсы
app/Models           — Eloquent-модели
app/Services         — бизнес-логика
database/migrations  — миграции
database/factories   — фабрики
database/seeders     — сиды
tests/Feature        — feature tests
```

### Frontend
```
src/entities    — типы сущностей
src/features    — бизнес-логика и API
src/pages       — маршрутизируемые страницы
src/widgets     — крупные UI-модули
src/shared      — базовые UI-компоненты и утилиты
src/app/router  — маршрутизация
src/layouts     — layout-компоненты
```

---

## Реализованные функциональные блоки

### 1. Auth foundation
**Реализовано:**
- регистрация студента;
- регистрация работодателя;
- вход;
- вход куратора/админа;
- me;
- logout.

**Основные backend endpoints:**
```
POST /api/auth/register/applicant
POST /api/auth/register/employer
POST /api/auth/login
POST /api/auth/login-curator
GET  /api/auth/me
POST /api/auth/logout
```

### 2. Applicant profile
**Реализовано:**
- автосоздание профиля;
- просмотр профиля;
- редактирование профиля;
- privacy settings;
- preferred work formats;
- preferred cities.

**Endpoints:**
```
GET  /api/applicant/profile
PUT  /api/applicant/profile
```

### 3. Public opportunities catalog
**Реализовано:**
- компании;
- теги;
- возможности;
- публичный каталог;
- фильтры;
- детальная карточка;
- сиды демо-данных.

**Endpoints:**
```
GET /api/opportunities
GET /api/opportunities/{id}
GET /api/tags
```

**Фильтры каталога:**
- search
- type
- work_format
- city
- tag

### 4. Favorites + applications
**Реализовано:**
- избранное;
- отклики;
- история откликов;
- сопроводительное письмо.

**Endpoints:**
```
GET    /api/applicant/favorites
POST   /api/applicant/favorites/{opportunity}
DELETE /api/applicant/favorites/{opportunity}

GET    /api/applicant/applications
POST   /api/applicant/applications
```

### 5. Employer company and opportunities
**Реализовано:**
- профиль компании;
- отправка на верификацию;
- создание возможностей;
- список собственных возможностей;
- редактирование;
- отправка на модерацию.

**Endpoints:**
```
GET  /api/employer/company
PUT  /api/employer/company
POST /api/employer/company/verification-submit

GET    /api/employer/opportunities
POST   /api/employer/opportunities
GET    /api/employer/opportunities/{id}
PUT    /api/employer/opportunities/{id}
POST   /api/employer/opportunities/{id}/submit
```

### 6. Employer revision flow
**Реализовано:**
- показ замечания куратора по компании;
- повторная отправка компании после доработки;
- показ замечания по возможности;
- редактирование возможности;
- повторная отправка карточки после исправлений.

### 7. Curator moderation
**Реализовано:**
- список компаний на верификации;
- список возможностей на модерации;
- approve / reject / needs revision;
- комментарий к модерации;
- журнал действий.

**Endpoints:**
```
GET    /api/curator/companies
PATCH  /api/curator/companies/{company}/status

GET    /api/curator/opportunities
PATCH  /api/curator/opportunities/{opportunity}/status

GET    /api/curator/audit-logs
```

### 8. Audit logs
**Реализовано:**
- запись изменений по модерации;
- логирование действий куратора;
- логирование создания кураторов.

### 9. Admin user management
**Реализовано:**
- список пользователей;
- фильтр по ролям;
- создание нового куратора.

**Endpoints:**
```
GET  /api/admin/users
POST /api/admin/users/curator
```

### 10. Карта возможностей
**Реализовано частично:**
- базовая интеграция Yandex Maps;
- отображение точек;
- выбор карточки по маркеру;
- режим списка / карты / гибрида.

**Требует:**
- валидного API key;
- финальной polish-настройки.

---

## Доменные сущности

### User
Поля:
- id
- display_name
- email
- password
- role

### ApplicantProfile
Поля:
- user_id
- full_name
- university_name
- course
- graduation_year
- about
- portfolio_url
- github_url
- privacy_level
- preferred_work_formats
- preferred_cities
- moderation_status
- moderation_comment

### Company
Поля:
- owner_user_id
- name
- description
- industry
- website_url
- social_url
- inn
- city
- address
- verification_status
- verification_comment
- verified_at

### Opportunity
Поля:
- company_id
- title
- short_description
- full_description
- type
- work_format
- employment_type
- level
- city
- address
- latitude
- longitude
- is_remote
- published_at
- expires_at
- event_date
- salary_from
- salary_to
- contacts_text
- external_url
- status
- moderation_comment

### Favorite
Поля:
- user_id
- opportunity_id
- planned_apply_at

### Application
Поля:
- opportunity_id
- applicant_profile_id
- cover_letter
- status
- employer_comment

### Tag
Поля:
- name
- slug
- group

### AuditLog
Поля:
- actor_user_id
- entity_type
- entity_id
- action
- old_status
- new_status
- comment

---

## Демо-аккаунты

Сиды создают базовые аккаунты.

| Роль          | Email                     | Password |
|---------------|---------------------------|----------|
| Администратор | admin@tramplin.local      | password |
| Куратор       | curator@tramplin.local    | password |
| Студент       | student@tramplin.local    | password |
| Работодатель  | employer@tramplin.local   | password |

**Важно:** admin и curator входят через `/login/curator`.

---

## Локальный запуск

### Backend
Установка и запуск через Sail.

**Основные команды:**
```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
./vendor/bin/sail test
```

**Полный reset:**
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

### Frontend
```bash
npm install
npm run dev
```

Если Vite-кэш поврежден:
```bash
rm -rf node_modules/.vite
npm run dev
```

---

## Полезные команды

### Backend tests
```bash
./vendor/bin/sail test
```

**Запуск одного тестового класса:**
```bash
./vendor/bin/sail artisan test --filter=ClassName
```

### Tinker
```bash
./vendor/bin/sail artisan tinker
```

**Проверка пользователя:**
```php
$user = \App\Models\User::query()->where('email', 'admin@tramplin.local')->first();
\Illuminate\Support\Facades\Hash::check('password', $user->password);
```

---

## Текущий статус проекта

### Уже реализовано
- auth;
- роли;
- applicant profile;
- opportunities catalog;
- filters;
- favorites;
- applications;
- employer profile;
- opportunity create/edit;
- revision flow;
- curator moderation;
- audit log;
- admin user management;
- базовая карта.

### Требует дополнительной шлифовки
- главная страница;
- единый product polish;
- пустые состояния;
- карта с валидным ключом;
- demo-ready упаковка;
- финальная конкурсная презентация.

---

## Известные нюансы

### 1. Yandex Maps API key
Если в консоли появляется ошибка `Invalid API key`, карта не сможет корректно инициализироваться.  
Нужен валидный ключ в frontend `.env`:
```
VITE_YANDEX_MAPS_API_KEY=
```

### 2. Admin login
Вход администратора идет не через `/login`, а через `/login/curator`.

### 3. Фильтры каталога
Фильтры уже были исправлены. Если снова перестанут работать, нужно первым делом проверить связку `OpportunityFilters.vue` + `OpportunitiesPage.vue` + обновление `catalogStore.filters` через `Object.assign`.

### 4. HomePage
Если снова появится ошибка `Failed to resolve component: UiButton`, нужно проверить именно тот файл `HomePage.vue`, который реально используется роутером.

---

## Ближайший следующий этап

Следующий логичный шаг разработки:

**Demo Polish Block** — шлифовка интерфейса и упаковка решения под конкурс.

Что планируется:
- усиленная главная;
- product polish;
- единая визуальная система статусов;
- сильная демонстрационная подача;
- подготовка сценария показа проекта.

---

## Демонстрационные сценарии

### Студент
1. Войти как студент.
2. Открыть каталог.
3. Отфильтровать возможности.
4. Добавить в избранное.
5. Отправить отклик.
6. Открыть историю откликов.

### Работодатель
1. Войти как работодатель.
2. Заполнить компанию.
3. Отправить компанию на верификацию.
4. Создать возможность.
5. Отправить ее на модерацию.
6. При необходимости исправить замечания.

### Куратор
1. Войти как куратор.
2. Проверить компанию.
3. Проверить возможность.
4. Одобрить / отклонить / отправить на доработку.
5. Открыть журнал действий.

### Администратор
1. Войти как admin.
2. Открыть список пользователей.
3. Создать нового куратора.
4. Проверить вход новым куратором.
