# Despark's igniCMS

**igniCMS** is an administrative interface builder for Laravel 5.4

## Prerequisites

 - nodejs >= 4.0
 - npm
 - bower
 - gulp
 - composer

## Installation

1. Require this package in your composer.json and run `composer update`:

  ```json
  "require": {
     "php": ">=5.5.9",
     "laravel/framework": "5.4.*",
     "despark/igni-core": "dev-v4.0-images"
  },
  ```

  Or `composer require despark/igni-core`

2. After composer update, insert service providers `Despark\Cms\Providers\AdminServiceProvider::class,` `Despark\Cms\Providers\IgniServiceProvider::class,` `Despark\Cms\Providers\EntityServiceProvider::class,`  `Despark\Cms\Providers\JavascriptServiceProvider::class,` before the _application service providers_ to the `config/app.php`

  **Example**

  ```php
   ...
    /*
    * Despark CMS Service Provider
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

3. Run this command in the terminal (it'll set all necessary resources to use the CMS. _To complete this step you should have **composer**, **npm** & **bower**, installed globally_):

  ```
    php artisan igni:install
  ```
  
4. Config your `config/auth.php` file to use Igni's User model

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

5. All done! Now go to the `<your_site_url>/admin` and use your credentials

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

## Copyright and License

Despark CMS was written by Despark for the Laravel framework and is released under the MIT License. See the LICENSE file for details.
