# Лабораторна робота 4 — HTTP API та OpenAPI. Валідація й обробка помилок

- **Студент:** Могилін Владислав Олександрович, група 6.1224
- **Варіант:** 7 — «Продаж одягу», тема `market-grid`
- **Стек:** Laravel 13, PHP 8.3, MySQL 8.4, Docker Compose, Scramble (OpenAPI)

Застосунок лабораторної №3 перенесено до `lab4`. До наявного каталогу (Blade-сторінки та `/api/catalog`)
додано REST API для продуктів і категорій із валідацією, обробкою помилок і документацією OpenAPI.

## Запуск

```bash
cp .env.example .env              # у PowerShell: Copy-Item .env.example .env
docker compose up -d
docker compose exec --user application app sh
# у контейнері:
composer install
php artisan key:generate
php artisan migrate --seed
```

Сайт і API: http://localhost:8081 (порт задається в `.env`: `APP_PORT` та `APP_URL`).
Окремий том MySQL створюється завдяки `COMPOSE_PROJECT_NAME=lab4`.
Команди `php artisan` виконуються в контейнері, Docker-команди з каталогу `lab4` в основній системі.

## API

Усі відповіді мають формат JSON. Для керування використовується числовий `id`, публічні сторінки
працюють зі slug. Продукт створюється з `category_id`, у відповіді повертаються і `category_id`, і назва `category`.

| Метод і адреса | Успіх | Помилки |
|---|---|---|
| `GET /api/products` | 200 (`data`, `links`, `meta`) | 422 |
| `GET /api/products/{product}` | 200 | 404 |
| `POST /api/products` | 201 + `Location` | 422 |
| `PATCH /api/products/{product}` | 200 | 404, 422 |
| `DELETE /api/products/{product}` | 204 без тіла | 404 |
| `GET /api/categories` | 200 | — |
| `GET /api/categories/{category}` | 200 | 404 |
| `POST /api/categories` | 201 + `Location` | 422 |
| `PATCH /api/categories/{category}` | 200 | 404, 422 |
| `DELETE /api/categories/{category}` | 204 | 404, 409 (категорія містить продукти) |
| `GET /api/catalog`, `GET /api/catalog/{slug}` | 200 | 404, 422 |

`PUT` не підтримується (405): оновлення часткове через `PATCH`.

## Розподіл відповідальності

Route → Form Request (правила входу) → Controller (координація) → Service (прикладна операція, правило
«категорію з продуктами не видаляти» через виняток `CategoryNotEmpty`) → API Resource (формат JSON).
Обробка помилок налаштована у `bootstrap/app.php` (`shouldRenderJsonWhen`, 404 і 409 для `api/*`).

## OpenAPI

Документація генерується пакетом Scramble:

- інтерактивний довідник: http://localhost:8081/docs/api
- специфікація: http://localhost:8081/docs/api.json
- файл у репозиторії: `api.json`

```bash
php artisan scramble:export --path=api.json    # виконувати в контейнері (потрібна база)
```

Відповіді 409 для видалення категорії та 404 для `/api/catalog/{slug}` додаються хуком
`Scramble::afterOpenApiGenerated()` в `app/Providers/AppServiceProvider.php`.

## Зупинка

```bash
docker compose stop      # дані MySQL зберігаються в томі lab4_mysql-data
```

`docker compose down -v` видаляє том разом із даними.