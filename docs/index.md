---
layout: default
---
# General CMS architecture
* [Models](#models)
* [Controllers](#controllers)
* [Migrations](#migrations)
* [Enteties](#enteties)
    * [What is an entity](#what-is-an-entity)
    * [How to use](#how-to-use)
    
# Commands
* [Create new resource](#create-new-resource)
* [Create Pages resource](#create-pages-resource)
* [Create Contacts resource](#create-contacts-page-resource)

# Features info
* [Form fields](#form-fields)
    * [Checkbox](#checkbox)
    * [Custom](#custom)
    * [Date picker](#date-picker)
    * [Datetime picker](#datetime-picker)
    * [Hidden](#hidden)
    * [Image single](#image-single)
    * [Many to many select](#many-to-many-select)
    * [Password](#password)
    * [Select](#select)
    * [Text](#text)
    * [Textarea](#textarea)
    * [Wysiwyg](#wysiwyg)
* [Image styles rebuilding](#image-styles-rebuilding)
* [Getting your uploaded images](#getting-your-uploaded-images)
* [Resetting passwords](#resetting-passwords)
* [Localization](#localization)
* [Change company logo](#change-company-logo)
* [Brute force protection](#brute-force-protection)

***

# General CMS acrchitecture
## Models
They will be stored at `app/Models`. There you must config your well know Laravel protected arrays, such as fillable and rules and also your relations to other models.
## Controllers
They will be stored at `app/Http/Controller/Admin`. By default it's a blank one extending Igni's `AdminController`. If you need to change the logic of some of the methods, you can copy->paste it from `AdminController` or `EntityController`  and edit it like you want.
## Migrations
They will be stored at `database/migrations`. There you must set your table name, columns and relations.
## Enteties
### What is an entity
Your resource's entity is like a spine for all of its views. You can find it in `config/enteties`. In it you can set which model/controller to use, desired actions, fields, columns and much more.
### How to use
#### name
This column is used to set your page title, browser tab title and add button name(E.g. you call it User, the button for creating a new one will be called "Add User").
#### model and controller
These are the columns where you can tell your entity which model and controller to use.
#### adminColumns
Here you can set which columns to show in your table at the index page. You can also use foreign keys here.

_Example_

You have a relation between a user and a car and you want to call the user's name and car's model from the user's entity:
```php
'adminColumns' => [
    'name',
    'car model' => 'car.model',
],
```
Here all 0/1 values are casted to No/Yes.
#### actions
You can also set your desired actions. By default they are set to all:
```php
'actions' => ['edit', 'create', 'destroy'],
```
#### adminFormFields
Here you can set all of your form inputs. For full information please read [Form fields](form-fields).
#### image_fields

_Example_

```php
'image_fields' => [
    'column_name' => [
        'thumbnails' => [
            'admin' => [
                'width' => 150,
                'height' => 150,
                'type' => 'resize',
            ],
            'normal' => [
                'width' => 400,
                'height' => 400,
                'type' => 'resize',
            ],
        ],
    ],
    'column_name_2' => [
        'thumbnails' => [
            'admin' => [
                'width' => 75,
                'height' => 75,
                'type' => 'resize',
            ],
            'normal' => [
                'width' => 200,
                'height' => 200,
                'type' => 'crop',
            ],
        ],
    ], 
],
```
Here you set all of your image field's settings. The admin settings are for creating a smaller thumbnail, showing it in the CMS while editing. The available types of image manipulations are `resize, crop and fit`. Keep in mind that these are normal sizes. If `retina_factor` is enabled in `config/ignicms.php` then you must upload double the size that is entered in `normal`(E.g. for column_name you must upload an image with minimum 800x800px).
#### adminMenu
Here is the place where you set the sidebar item shown in the CMS. You can make normal or nested sidebar lists. In order to make a nested sidebar list you must config the parent once in some entity, after that you can add children to him with the `parent` column. The `weight` column is not required. It is used to sort your items in a way that you prefer. In the example below, the User management will be always on top of the sidebar items and the nested items will be Users and after that Roles. 

_Example_

Nested sidebar list

`config/enteties/users.php`

```php
'adminMenu' => [
    'user_management' => [
        'name' => 'User Management',
        'iconClass' => 'fa-users',
        'weight' => 1,
    ],
    'users' => [
        'name' => 'User',
        'link' => 'user.index',
        'parent' => 'user_management',
        'weight' => 2,
    ],
],
 ```
 
 `config/enteties/roles.php`
 
 ```php
'adminMenu' => [
    'roles' => [
        'name' => 'Role',
        'link' => 'role.index',
        'parent' => 'user_management',
        'weight' => 3,
    ],
],
 ```
 
_Example_

Normal sidebar list

`config/enteties/users.php`

 ```php
'adminMenu' => [
    'users' => [
        'name' => 'User',
        'link' => 'user.index',
        'iconClass' => 'fa-users',
        'weight' => 1,
    ],
],
 ```

# Commands
## Create new resource
Use the command `php artisan igni:make:resource` to create all necessary files for manipulating resources. You should specify the resource name (in title case).

_Example_

  ```
    php artisan igni:make:resource "Blog Post"
  ```
## Create Pages resource
Use the command `php artisan igni:make:pages` to create all necessary files for manipulating a Pages resource. With this command only, you can start building your pages for the new awesome site!

_Example_

  ```
    php artisan igni:make:pages
  ```
## Create Contacts page resource
If you want a command for creating a Contacts page resource, you should add our extension to IgniCMS. You can find full information about it [here](https://github.com/despark/igni-contact-us).

# Features info
## Form fields
### Checkbox
```php
'column_name' => [
    'type' => 'checkbox',
    'label' => 'I am a checkbox',
],
```
### Custom
Of course that you can make a custom field. You can accomplish it by creating a Handler, Template and Factory. As an example, we will make a color picker. You choose where to store the files. For this example, our handler will be created in `app/Fields`:
```php
use Despark\Cms\Contracts\FieldContract;
use Despark\Cms\Fields\Custom;

/**
 * Class Color.
 */
class Color extends Custom implements FieldContract
{
    protected $model;
    protected $fieldName;
    protected $value;
    protected $options;

    /**
     * Color constructor.
     *
     * @param Custom $parent
     */
    public function __construct($fieldName, array $options, $value = null, $model = null)
    {
        $this->model = $model;
        $this->fieldName = $fieldName;
        $this->value = $value;
        $this->options = $options;

        if (! isset($options['template'])) {
            throw new \Exception('Template is required for field '.$fieldName);
        }

        if (isset($options['template']) && \View::exists($options['template'])) {
            $this->template = $options['template'];
        }
    }

    public function getModel()
    {
        return $this->model;
    }
}
```
Our factory class will be stored at `app\Factories`:
```php
use Despark\Cms\Fields\Contracts\Factory;
use App\Fields\Color;

class ColorFactory implements Factory
{
    public function make(array $data)
    {
        extract($data);

        $field = new Color($field, $options, $value, $model);

        return $field;
    }
}
```
Let's create a template in `app/resources/views/admin` called `color.blade.php`:
```php
<div class="form-group {{ $errors->has($field->getFieldName()) ? 'has-error' : '' }}">
    {!! Form::label($field->getFieldName(), $field->getOptions('label')) !!}
    <div class="input-group my-colorpicker2">
        {!! Form::text($field->getFieldName(), $field->getModel()->active_color, [
            'id' =>  $field->getFieldName(),
            'class' => "form-control",
            'placeholder' => $field->getOptions('label'),
        ] ) !!}

        <div class="input-group-addon">
            <i></i>
        </div>
    </div>
    <div class="text-red">
        {{ join($errors->get($field->getFieldName()), '<br />') }}
    </div>
</div>

@push('additionalScripts')
    <script type="text/javascript">
        $(".my-colorpicker2").colorpicker({
            format: 'hex'
        });
    </script>
@endpush
```
Finally, let's call our field:
```php
'column_name' => [
    'type' => 'custom',
    'handler' => \App\Fields\Color::class,
    'template' => 'admin.color',
    'factory' => \App\Factories\ColorFactory::class,
    'label' => 'I am a custom field',
],
```

### Date picker
```php
'column_name' => [
    'type' => 'date',
    'label' => 'I am a date picker',
],
```
### Datetime picker
```php
'column_name' => [
    'type' => 'datetime',
    'label' => 'I am a datetime picker',
],
```
### Hidden
```php
'column_name' => [
    'type' => 'hidden',
],
```
### Image single
Keep in mind that the images relation is polymorphic, so you don't have to make a column in your table. You just need to replace `column_name` with your desired one.
```php
'column_name' => [
    'type' => 'imageSingle',
    'label' => 'I am a image single upload',
],
```
### Many to many select
First, we need to make the many to many relation between the Models. For this example we are using a User and Permission class.
```php
use Despark\Cms\Models\AdminModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends AdminModel implements UserContract, CanResetPasswordContract
{
    use Notifiable;
    use Authenticatable, Authorizable, CanResetPassword;

    ...

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|max:20',
        'permissions' => 'required',
    ];

    ...
    
    // Here we define the method_name => request_column_name
    public function getManyToManyFields()
    {
        return [
            'permissions' => 'permissions',
        ];
    }
    
    // Here we define a relation to the Permission class
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
```

```php
use Despark\Cms\Models\AdminModel;

class Permission extends AdminModel
{
    protected $table = 'permissions';

    protected $fillable = ['name'];

    protected $rules = ['name' => 'required|max:50'];

    // Here we define a relation to the User class
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    protected $identifier = 'permission';
}
```
Next we need to get the data for the Select. For example in our `app/Sources` directory, we can make a Permissions class and get the needed data:
```php
use App\Models\Permission;
use Despark\Cms\Contracts\SourceModel;

/**
 * Class Permissions.
 */
class Permissions implements SourceModel
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @return array
     */
    public function toOptionsArray()
    {
        if (! isset($this->options)) {
            $this->options = Permission::orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }

        return $this->options;
    }
}
```
Finally, we need to setup the field in our entity:
```php
'column_name[]' => [ // In our case permissions[]
    'type' => 'manyToManySelect',
    'label' => 'Permissions',
    'additionalClass' => 'select2-tags', // This is not required. Use it if you need some extra classes
    'sourceModel' => \App\Sources\Permissions::class,
    'relationMethod' => 'permissions', // The name of the relation
    'validateName' => 'permissions', // Which name to validate in the protected $rules array
    'selectedKey' => 'id', // Which key will be the value for the select
],
```
It also works with polymorphic relations. You can find more info about this type of relations in the [Laravel docs](https://laravel.com/docs/5.5/eloquent-relationships#many-to-many-polymorphic-relations)
### Password
```
'column_name' => [
    'type' => 'password',
    'label' => 'I am a password',
],
```
### Select
In order to use the select field, you need to build up a Source model class. Here is an example Roles class:
```php
use Despark\Cms\Contracts\SourceModel;
use App\Models\Role;

/**
 * Class Roles.
 */
class Roles implements SourceModel
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @return array
     */
    public function toOptionsArray()
    {
        if (! isset($this->options)) {
            $this->options = Role::orderBy('name')->pluck('name', 'id')->toArray();
        }

        return $this->options;
    }
}

```
And here is how to call the field:
```php
'column_name' => [
    'type' => 'select',
    'label' => 'I am a select',
    'sourceModel' => \App\Sources\Roles::class,
],
```
### Text
```php
'column_name' => [
    'type' => 'text',
    'label' => 'I am a text',
],
```
### Textarea
```php
'column_name' => [
    'type' => 'textarea',
    'label' => 'I am a textarea',
],
```
### Wysiwyg
```php
'column_name' => [
    'type' => 'wysiwyg',
    'label' => 'I am a wysiwyg',
],
```
## Image styles rebuilding
You can rebuild image styles using `php artisan igni:images:rebuild` . If you want you can specify which resources to rebuil with `--resources=*` switch.
You can exclude some resources with `--without=*`

## Getting your uploaded images
You can get all images for a given resoruce with the following function:
```php
    $image = $model->getImages('image')->first()
```
where ```image``` is the id field given in the resource config file.
To display the images in your view you can use the following function:
```php
    {!! $image->toHtml('normal') !!}
```
where ```normal``` is the given image type in the resource config file.
Example resource config file:
```php
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

