---
layout: default
---

# Commands
* [Create new resource](#create-new-resource)
* [Create Pages resource](#create-pages-resource)
* [Create Contacts resource](#create-contacts-page-resource)

# Features info
* [Image styles rebuilding](#image-styles-rebuilding)
* [Getting your uploaded images](#getting-your-uploaded-images)
* [Resetting passwords](#resetting-passwords)
* [Localization](#localization)
* [Change company logo](#change-company-logo)
* [Brute force protection](#brute-force-protection)

***


# Commands
## Create new resource
Use the command `php artisan igni:make:resource` to create all necessary files for manipulating resources. You should specify the resource name (in title case).

**Example**

  ```
    php artisan igni:make:resource "Blog Post"
  ```
## Create Pages resource
Use the command `php artisan igni:make:pages` to create all necessary files for manipulating a Pages resource. With this command only, you can start building your pages for the new awesome site!

**Example**

  ```
    php artisan igni:make:pages
  ```
## Create Contacts page resource
If you want a command for creating a Contacts page resource, you should add our extension to IgniCMS. You can find full information about it [here](https://github.com/despark/igni-contact-us).

# Features info
## Image styles rebuilding
You can rebuild image styles using `php artisan igni:images:rebuild` . If you want you can specify which resources to rebuil with `--resources=*` switch.
You can exclude some resources with `--without=*`

## Getting your uploaded images
You can get all images for a given resoruce with the following function:
```
    $image = $model->getImages('image')->first()
```
where ```image``` is the id field given in the resource config file.
To display the images in your view you can use the following function:
```
    {!! $image->toHtml('normal') !!}
```
where ```normal``` is the given image type in the resource config file.
Example resource config file:
```
'image_fields' => [
        'image' => [
            'thumbnails' => [
                'admin' => [
                    'width' => 150,
                    'height' => null,
                    'type' => 'resize',
                ],
                'normal' => [
                    'width' => 600,
                    'height' => 368,
                    'type' => 'resize',
                ],
            ],
        ],
    ],
```
## Resetting passwords
In order to use the reset password function, you must fill up the MAIL and APP settings in your `.env` file or modify your default ones in `config/app.php` and `config/mail.php`.

```
    ...
    APP_NAME=IgniCMS
    APP_URL=http://my-site-url.com
    ...
    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_FROM_ADDRESS=no-reply@ignicms.com
    MAIL_FROM_NAME=IgniCMS
```
By modifying your APP_URL, the email sent to the user will be working as expecred!
If you want to change your email's template, you can find out how at [Laravel's website](https://laravel.com/docs/5.4/mail#customizing-the-components).

## Localization
IgniCMS comes out of the box with localization. You can find full information about it [here](https://github.com/despark/laravel-db-i18n).


## Change company logo
You can change the company logo from `config/ignicms.php`.
  ```php
   ...
    // For best performance the image must be with width 234px
    'logo' => 'images/logo.png',
   ...
  ```

## Brute force protection
If someone tries to login with a certain email and makes 5 wrong attempts, this email will be blocked for 15 minutes.

