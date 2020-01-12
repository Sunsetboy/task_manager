## Tech specs
* PHP 7.3
* MariaDB 10.3
* Nginx 1.17

## Running
First run:
* Copy the file html/tasks/.env.example to .env and change settings
* Copy the file .env.example to .env and change settings
* Execute
```docker-compose build```, then ```docker-compose up -d```

Second and further runs: ```docker-compose up -d```

**Entrance to the application container**
```
docker exec -ti tasks bash
```
During the first run install dependencies and migrations
```
composer install
php artisan migrate
```

## Остановка стенда
```docker-compose down```

## Тестовый режим
В тестовом режиме в очередь обзвона отправляются не реальные данные клиентов, а тестовые (мои и Никиты). Включение/выключение тестового режима  осуществляется через файл html/callcenter/.env
```
EXPORT_TEST_PHONES=true
```

## Запуск тестов
Зайти в контейнер callcenter

Запуск тестов
```
vendor/bin/phpunit
```
Запуск тестов с отчетом по покрытию
```
vendor/bin/phpunit --coverage-html tests/output/coverage/ --coverage-text
```
Отчет по покрытию будет сгенерирован в папке tests/output/coverage

## Gitlab CI
При пуше в репозиторий Gitlab автоматически запускаются тесты. Если вы внесли изменения в файлы, относящиеся к Докеру, например, в образы, необходимо пересобрать и запушить изменения в Докер репозиторий.

Для этого перейдите в папку docker/php-fpm и выполните команды:
```
docker build -t hub.4slovo.ru/4slovo.ru/callcenter .
docker push hub.4slovo.ru/4slovo.ru/callcenter
```