## TODO Manager

This is a web application that allows you to create, edit, delete and organize your tasks according to your priority, these will be saved for each user and can only be modified by him.
It was developed with Laravel 5.6 as a backend and Vue Js as a frontend, this is an API

## Installation

First at all download the repository.
```

$ git clone https://github.com/santiagoapp/My-First-To-Do-App.git

```
Next to this update through Composer.
```

$ composer update

```
and then move into the folder and generate .env configuration file
```

$ copy /y .env.example .env

```
Finally, generate a new application key:
```

$ php artisan key:generate

```
Also you need to download npm dependencies:
```

$ npm install

```
you can run the migrations after you set your database:
```

$ php artisan migrate

```
and optionally you can run seeders in order to initialize a set of tasks and a generic user:
```

$ php artisan db:seed

```
user: santiagoapp@gmail.com
pass: secret

Best wishes