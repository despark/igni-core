<p align="center"><img src="https://despark.com/public/images/despark-logo.svg"></p>

<p align="center">
<a href="https://packagist.org/packages/despark/igni-core#4.1.x-dev"><img src="https://poser.pugx.org/despark/igni-core/v/stable.svg" alt="Latest Stable Version"></a>
</p>

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
    * igniCMS Service Providers
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

## Additional information

- You can find more info about IgniCMS in our <a href="https://github.com/despark/igni-core/wiki/IgniCMS-Wiki">wiki</a> page.

## Copyright and License

igniCMS was written by Despark for the Laravel framework and is released under the MIT License. See the LICENSE file for details.
