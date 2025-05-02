<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"><h2 align="center">11</h2></a></p>


<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Video
<iframe width="560" height="315" src="https://www.youtube.com/embed/0ZUQy8vepns?si=i8wW2RUXSmAy7trH" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

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
- [Queues](https://laravel.com/docs/11.x/queues) with RabbitMQ or Redis driver.
- [Real-time event broadcasting](https://laravel.com/docs/11.x/broadcasting) with [Reverb](https://laravel.com/docs/11.x/reverb).
- Searching with [Scout](https://laravel.com/docs/11.x/scout) using [Meilisearch](https://www.meilisearch.com/docs) driver.
- Multiple dashboard with [jetstream](https://jetstream.laravel.com/introduction.html) and [filament](https://filamentphp.com/docs) builder.
- Implement chat room application with [reverb](https://laravel.com/docs/11.x/reverb) and [livewire](https://livewire.laravel.com/docs/quickstart) template.
- Implement Laravel [Octane](https://laravel.com/docs/11.x/octane) as web server.
- Implement Laravel [Sail](https://laravel.com/docs/11.x/sail).
- Include monitoring dashboard [Laravel Pulse](https://laravel.com/docs/11.x/pulse) and [Laravel Telescope](https://laravel.com/docs/11.x/telescope).
- Implement [encrypt|decrypt](https://laravel.com/docs/11.x/encryption) string on sesitive data.


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
php artisan install:api
php artisan install:broadcasting
php artisan telescope:install

php artisan migrate
```

```bash
npm run dev
```

Run via composer (recomended):

```bash
composer run dev
```
OR

Run artisan server (recomended):

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

This step is required to running third party services in docker container, all docker-compose.yml is located on docker-compose directory:

```bash
cd docker-compose
```

mailpit (required)
```bash
cd mailpit
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
php artisan queue:listen rabbitmq --verbose
php artisan queue:listen redis --verbose
```

#### Optional RabbitMQ cunsumer
```bash
php artisan rabbitmq:consume
php artisan queue:listen
```

#### Run seeder (OPTIONAL):
```bash
php artisan db:seed --class=TestingSeeder
php artisan db:seed --class=AlbumSeeder
php artisan db:seed --class=MediaLibrarySeeder
```

#### Linking storage (REQUIRED):
```bash
php artisan storage:link
```

#### Sync Scout(REQUIRED):
```bash
php artisan scout:sync-index-settings
php artisan scout:import "App\Models\Testing"
php artisan scout:import "App\Models\Album"
php artisan scout:import "App\Models\MediaLibrary"
```

### Run test:

```bash
php artisan migrate --env=testing
php artisan test > test-results.txt
```
open test-results.txt to view the results


### Default Url :
View on browser [http://127.0.0.1:8000](http://127.0.0.1:8000), register new user or more to access the main dashboard.

### Dashboard:

Jetstream dashboard [http://127.0.0.1:8000/dashboard](http://127.0.0.1:8000/dashboard)

Filament dashboard  [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

Pulse dashboard [http://127.0.0.1:8000/pulse](http://127.0.0.1:8000/pulse)

Telescope dashboard [http://127.0.0.1:8000/telescope](http://127.0.0.1:8000/telescope)

### Status Server:
Web : [http://127.0.0.1:8000/status](http://127.0.0.1:8000/status)

API : [http://127.0.0.1:8000/api/status](http://127.0.0.1:8000/api/status)

### API:
API Resources:

Testing [http://127.0.0.1:8000/api/testing](http://127.0.0.1:8000/api/testing)

Album [http://127.0.0.1:8000/api/album](http://127.0.0.1:8000/api/album)

Media [http://127.0.0.1:8000/api/media](http://127.0.0.1:8000/api/media)


Required Set Custom Header (must equals as env value):
```text
Key = env.APP_HEADER_CUSTOM_KEY
Value = env.APP_HEADER_CUSTOM_VALUE
```

Required Bearer Token (must equals as generated token):

Create token from profile dropdown menu "API Tokens"


Sample search data ([http://127.0.0.1:8000/api/testing/search]).

Note: Sync Scout data to serach engine(meilisearch) before hit.
```text
// Body - form-data, method: POST
// route : api/testing/search | api/album/search | api/media/search
// Key: q, Value: any text
q : a
```

Sample insert | update data.
```json
// New User
{
    "email" : "admin@example.com",
    "password" : "password123",
    "device_name" : "mobile"
}

// New Testing Data
{
    "name" : "Test data",
    "description" : "Description of test",
    "image": "http://127.0.0.1:8000/media/1/image.jpg"
}

// New Album Data
{
    "name" : "Test data",
    "description" : "Description of test",
    "image": "http://127.0.0.1:8000/media/1/image.jpg"
}

// New Media Library Data
{
    "album_id": "01jp4k7vhfwr3ekx4z3k1754kg",
    "image": "http://127.0.0.1:8000/media/1/image.jpg",
    "name": "Test data",
    "intro": "Intro of test",
    "description": "Description of test"
}

```

Sample Errors Response.
```json
// Invalid Custom Header
{
    "message": "Access Denied!",
    "statusCode": 403,
    "errors": "Invalid request."
}

// Invalid Auth
{
    "message": "Forbidden.",
    "statusCode": 403,
    "errors": "Unauthenticated."
}

// Access Denied - Invalid Policy
{
    "message": "Not accessable.",
    "statusCode": 403,
    "errors": "This action is unauthorized."
}

// Invalid Route or Requested Id
{
    "message": "Record not found.",
    "statusCode": 404,
    "errors": "Not found."
}
```

Sample Successfull Response.
```json
{
    "data": {
        "id": 1,
        "name": "LutviP 19",
        "description": "Rem possimus consequatur ut fuga. Mollitia impedit ad vel esse eius sint.",
        "image": "http://localhost:8000/storage/7/image.jpg",
        "created_at": "2025-03-11T06:49:14.000000Z",
        "updated_at": "2025-03-11T06:49:14.000000Z",
        "can": {
            "read": true,
            "create": true,
            "update": true,
            "delete": true
        }
    },
    "meta": {
        "app": "Laravel",
        "version": "1.0.0",
        "meta-key": "meta-value"
    }
}
```


## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
