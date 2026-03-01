# Дипломный проект

## Описание

Special for Friend Lee.

Бэкенд часть приложения совместных покупок (realtime todo list).

В проект был интегрирован Filament.

---

## Важное

В Filament **не были настроены права для пользователей**, поэтому его 
стоит рассматривать не как полноценный личный кабинет или админ-панель, 
а как инструмент для: 
- дебага 
- тестирования 
- демонстрации встраивания Filament в существующую архитектуру

---

## Сборка проекта

### 1. Клонировать проект

``` bash
git clone https://github.com/waffflezz/cobuy_server_gitlab.git
cd cobuy_server_gitlab
```

### 2. Добавить файл `.env`

``` bash
cp src/.env.example src/.env
```

Для dev-среды ничего менять в `.env` не требуется всё уже настроено.
Этого достаточно для демонстрации работы проекта.

---

### 3. Поднять контейнеры

``` bash
docker compose up -d app_dev db_dev
```

---

### 4. Сгенерировать ключ, создать ссылку на storage и создать пользователя

Проваливаемся в контейнер:
``` bash
docker exec -it laravel_dev bash
```

Уже в контейнере:
```bash
php artisan key:generate
php artisan storage:link
php artisan make:filament-user
exit
```

---

## Доступ к проекту

http://localhost:8000/admin - Админ панель Filament
