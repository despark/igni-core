# Despark's igniCMS

**igniCMS** is an administrative interface builder for Laravel 5.4

## Prerequisites

 - nodejs >= 4.0
 - yarn or npm
 - bower
 - gulp
 - composer

## Installation

1. Require this package in your composer.json and run `composer update`:

  ```json
  "require": {
     "php": ">=5.5.9",
     "laravel/framework": "5.4.*",
     "despark/igni-core": "4.1.x-dev"
  },
  ```

  Or `composer require despark/igni-core`

2. Add igniCMS service providers before the _application service providers_ in the `config/app.php`, as shown below 

  **Example**

  ```php
   ...
    /*
    * igniCMS Service Provider
    */
    Despark\Cms\Providers\AdminServiceProvider::class,
    Despark\Cms\Providers\IgniServiceProvider::class,
    Despark\Cms\Providers\EntityServiceProvider::class,
    Despark\Cms\Providers\JavascriptServiceProvider::class,

    /*
    * Package Service Providers...
    */
    Laravel\Tinker\TinkerServiceProvider::class,
   ...
  ```
  
3. Config your database settings in your `.env` file.

```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=mydbw
    DB_USERNAME=user
    DB_PASSWORD=password
```

4. Run this command in the terminal (it'll set all necessary resources to use the CMS. _To complete this step you should have **composer**, **npm** & **bower**, installed globally_):

  ```
    php artisan igni:install
  ```
  
5. Config your `config/auth.php` file to use Igni's User model

**Example**

 ```php
   ...
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
   ...
  ```

6. All done! Now go to the `<your_site_url>/admin` and use your credentials

## Additional commands

- Use the command `php artisan igni:resource` to create all necessary files for manipulating resources. You should specify the resource name (in title case).

  **Example**

  ```
    php artisan igni:resource "Blog Post"
  ```

- Use the command `php artisan igni:make:pages` to create all necessary files for manipulating a Page resources. With this command only, you can start building your pages for the new awesome site!

### Image styles rebuilding ###
You can rebuild image styles using `php artisan igni:images:rebuild` . If you want you can specify which resources to rebuil with `--resources=*` switch.
You can exclude some resources with `--without=*`

### Resetting passwords ###
In order to use the reset password function, you must fill up the MAIL settings in your `.env` file.

```
    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=
    MAIL_PASSWORD=
```

## Copyright and License

igniCMS was written by Despark for the Laravel framework and is released under the MIT License. See the LICENSE file for details.
