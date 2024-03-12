# Тестовое задание DailyGrow "Рассылка"

Серверная часть

Фронтенд часть: https://github.com/dijeth-example-tasks/dailygrow-ui

## Установка

1. Склонировать проект
    ```
    git@github.com:dijeth-example-tasks/dailygrow-sms.git
    ```
1. Поднять БД
    ```
    docker compose -f ./docker/docker-compose.dev.yml up -d
    ```
1. Создать и заполнить .env
    ```
    cp .env.example .env
    php artisan key:generate
    ```
1. Запустить миграции
    ```
    php artisan migrate
    ```
1. Заполнить БД тестовыми данными

    ```
    php artisan db:seed
    ```

    Команда создает две группы (сегмента) клиентов рассылок, несколько рассылок и демо-юзера **demo-user@email.com (пароль "demo-user")**,

    _Для добавления дополнительного юзера:_

    ```
    php artisan app:create-user
    ```

    _Для изменения тестовых данных отредактируйте файл **database/seeders/DatabaseSeeder.php**_

1. Запустить дев-сервер
    ```
    php artisan serve
    ```
1. Далее можно либо запустить крон (в локальном режиме происходит имитация работы крона)
    ```
    php artisan schedule:work
    ```
    либо разово выполнить крон, имитируя его запуск в данный момент
    ```
    php artisan app:send-sms
    ```
