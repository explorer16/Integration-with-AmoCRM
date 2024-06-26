# Laravel amoCRM Integration Project

## Описание

Этот проект демонстрирует интеграцию с amoCRM с использованием Laravel. Включены функции авторизации на сервисе amoCRM, создание сделки и добавление комментариев к сделке. Это отличный пример использования Laravel для взаимодействия с внешними API.

## Технологии

Проект построен с использованием следующих технологий:

- **Laravel**: фреймворк для веб-приложений на PHP.
- **amoCRM API**: интерфейс для взаимодействия с amoCRM.

## Установка

Для локальной установки проекта выполните следующие шаги:

1. Клонируйте репозиторий на свой компьютер:
    ```bash
    git clone https://github.com/yourusername/your-repo-name.git
    ```

2. Перейдите в директорию проекта:
    ```bash
    cd your-repo-name
    ```

3. Установите зависимости с помощью Composer:
    ```bash
    composer install
    ```

4. Скопируйте файл настроек:
    ```bash
    cp .env.example .env
    ```

5. Настройте переменные среды для подключения к amoCRM в файле `.env`:
    ```env
    AMOCRM_CLIENT_ID=your_client_id
    AMOCRM_CLIENT_SECRET=your_client_secret
    AMOCRM_REDIRECT_URI=your_redirect_uri
    AMOCRM_SUBDOMAIN=your_subdomain
    ```

6. Сгенерируйте ключ приложения:
    ```bash
    php artisan key:generate
    ```

7. Запустите сервер разработки:
    ```bash
    php artisan serve
    ```

Теперь ваше приложение доступно по адресу `http://localhost:8000`.

## Структура проекта

- **routes/web.php**: файл маршрутов веб-приложения.
- **app/Http/Controllers**: директория с контроллерами.
- **resources/views**: директория с Blade шаблонами.

## Основные функции

### Авторизация в amoCRM

Для авторизации в amoCRM используется OAuth2. В проекте реализован механизм перенаправления пользователя на страницу авторизации и обработки кода авторизации для получения токенов доступа.

### Создание сделки

После успешной авторизации, приложение может создавать сделки в amoCRM с использованием их API.

### Добавление комментариев

К каждой созданной сделке могут быть добавлены комментарии.
