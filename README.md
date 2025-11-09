# Laravel + Vue 3 Docker Project

## Описание

Fullstack приложение с системой управления балансом пользователей:
- **Backend**: Laravel 11 (PHP 8.2)
- **Frontend**: Vue 3 + Vite
- **Database**: MySQL 8.0
- **Cache/Queue**: Redis
- **Styling**: Bootstrap 5 + SCSS

---

## Требования

- Docker >= 24.x
- Docker Compose >= 2.x

---

## Быстрый старт
```bash
# Клонируем репозиторий
git clone <repository-url>
cd <project-folder>

# Собираем и запускаем контейнеры
docker compose build
docker compose up -d

# Запуск queue worker
docker compose exec -d app php artisan queue:work redis --queue=balance
```

---

## Доступ к приложению

| Сервис | URL | Порт |
|--------|-----|------|
| Laravel Backend | http://localhost:44488 | 44488 |
| Vue Frontend | http://localhost:51733 | 51733 |
| MySQL | localhost:33061 | 33061 → 3306 |
| Redis | localhost:6379 | 6379 |

---

## CLI Команды
```bash
# Добавить пользователя
docker compose exec app php artisan user:add "Name"

# Управление балансом
docker compose exec app php artisan balance-transaction:add "Name"

# Просмотр логов
docker compose logs -f app

# Остановка сервисов
docker compose down
```

---

## API

**Заголовки для POST запросов:**
```
Content-Type: application/json
Accept: application/json
```

---

## Troubleshooting
```bash
# Исправление прав доступа
docker compose exec app chmod -R 775 storage bootstrap/cache

# Очистка кэша
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear

# Пересборка контейнеров
docker compose down
docker compose build --no-cache
docker compose up -d
```