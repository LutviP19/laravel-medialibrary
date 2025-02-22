<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"><h2 align="center">11</h2></a></p>


<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About

Laravel 11 implementations for starter kit web application. Development must be an enjoyable and creative experience to be truly fulfilling. Laravel media library is takes the pain out of starter kit development used in web projects, in this project is implemented several features of Laravel framework, such as:

- [Simple, fast routing engine](https://laravel.com/docs/11.x/routing).
- [Middleware](https://laravel.com/docs/11.x/middleware).
- [Resource Controllers](https://laravel.com/docs/11.x/controllers#resource-controllers).
- [Form Request Validation](https://laravel.com/docs/11.x/validation#form-request-validation).
- [Error Handling](https://laravel.com/docs/11.x/errors).
- [Writing Commands](https://laravel.com/docs/11.x/artisan#writing-commands).
- [Eloquent: Relationships](https://laravel.com/docs/11.x/eloquent-relationships).
- [Collections](https://laravel.com/docs/11.x/collections).
- [Events](https://laravel.com/docs/11.x/events) with [Listeners](https://laravel.com/docs/11.x/events).
- Database tools [schema migrations](https://laravel.com/docs/migrations).
- [Queues](https://laravel.com/docs/11.x/queues).
- [Real-time event broadcasting](https://laravel.com/docs/11.x/broadcasting) with RabbitMQ or Redis driver.
- Multiple dashboard with [jetstream](https://jetstream.laravel.com/introduction.html) and [filament](https://filamentphp.com/docs) builder.
- Implement chat room application with [reverb](https://laravel.com/docs/11.x/reverb) and [livewire](https://livewire.laravel.com/docs/quickstart) template.
- Implement Laravel [Octane](https://laravel.com/docs/11.x/octane) as web server.
- Implement Laravel [Sail](https://laravel.com/docs/11.x/sail).
- Include monitoring dashboard [Laravel Pulse](https://laravel.com/docs/11.x/pulse) and [Laravel Telescope](https://laravel.com/docs/11.x/telescope).


## How to run

Follow this steps to run this project.

Install depedencies:

```bash
composer install
```

```bash
npm install
```

Build the project:

Modify env to correct database connections.

```bash
php artisan install:broadcasting
php artisan telescope:install

php artisan migrate
```

```bash
npm run dev
```

Run artisan server:

```bash
php artisan serve
```
OR

Run octane server:

```bash
php artisan octane:install
php artisan octane:start --watch
```
OR

Run with docker:

```bash
./vendor/bin/sail up
```



#### Compose docker container (REQUIRED):

This step is required to support realtime apps, all docker-compose.yml is located on docker-compose directory:

```bash
cd docker-compose
```

mailhog (required)
```bash
cd mailhog
docker-compose up
```

rabbitmq (required)
```bash
cd ../rabbitmq-python
docker-compose up
```

redis (required)
```bash
cd ../redis
docker-compose up
```

LocalStack (optional)
```bash
cd ../LocalStack
docker-compose up
```

#### Run services on separated terminals (REQUIRED):

```bash
php artisan reverb:start --debug
php artisan queue:listen
php artisan queue:listen redis
php artisan rabbitmq:consume
```

#### Url :
View on browser [http://127.0.0.1:8000](http://127.0.0.1:8000), register new user or more to access the main dashboard.

## Dashboard:

Jetstream dashboard [http://127.0.0.1:8000/dashboard](http://127.0.0.1:8000/dashboard)

Filament dashboard  [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

Pulse dashboard [http://127.0.0.1:8000/pulse](http://127.0.0.1:8000/pulse)

Telescope dashboard [http://127.0.0.1:8000/telescope](http://127.0.0.1:8000/telescope)

API Resource Testing [http://127.0.0.1:8000/api/testing](http://127.0.0.1:8000/api/testing)

Sample insert | update data.
```json
// New Data
{
    "name" : "Test data",
    "description" : "Description of test",
    "image" : "http://localhost:4566/sample-bucket/image.jpg?AWSAccessKeyId=test&Signature=bO0naJ0IClTaTznV5cWNV5iqMe4%3D&Expires=1739254435"
}
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
