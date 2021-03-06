## Tech specs
* PHP 7.3
* MariaDB 10.3
* Nginx 1.17
* Laravel Lumen framework

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
php artisan db:seed
```
## Application URL
Application is available by URL ```localhost:81```

## Postman collection
Collection of routes for Postman is attached in the postman folder

## Stopping the application
```docker-compose down```

## Tests execution

```
docker exec tasks vendor/bin/phpunit
```