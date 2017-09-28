---
layout: default
---
# General CMS architecture
* [Models](#models)
* [Controllers](#controllers)
* [Migrations](#migrations)
* [Entities](#entities)
    * [What are entities?](#what-are-entities)
    * [How to use them?](#how-to-use-them)
    
# Commands
* [Create new resource](#create-new-resource)
* [Create Pages module](#create-pages-madule)
* [Create Contacts module](#create-contacts-module)
* [Image rebuilding](#image-rebuilding)

# Features info
* [Form fields](#form-fields)
    * [Checkbox](#checkbox)
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
    * [Custom](#custom)
* [Images](#images)
    * [Check if the resource has images](check-if-the-resource-has-images)
    * [Get uploaded images](get-uploaded-images)
    * [Get image model](get-image-model)
    * [Get image relation](get-image-relation)
    * [Get minimal dimensions for an image](get-minimal-dimensions-for-an-image)
    * [Get retina factor](get-retina-factor)
    * [Get required form images](get-required-form-images)
    * [Get image fields and meta fields](get-image-fields-and-meta-fields)
    * [Get upload directory](get-upload-directory)
    * [Creating a thumbnail](creating-a-thumbnail)
    * [Set image model](set-image-model)
    * [Set minimal dimensions](set-minimal-dimensions)
    * [Set retina factor](set-retina-factor)
* [Resetting passwords](#resetting-passwords)
* [Localization](#localization)
* [Change CMS logo](#change-cms-logo)
* [Brute force protection](#brute-force-protection)

***

# General CMS acrchitecture
## Models
The models which are generated for resources using the `igni:` commands will be stored in the `app/Models` directory. Those models follow the standard Laravel convention, so you must input the well know Laravel protected arrays, e.g. `$fillable` and `$rules` and also the relationships with other models. We advise you to store all the models in the `app/Models` directory (even the ones which are not generated using `igni:` commands) in order to keep consistency within your project architecture.
## Controllers
Those generated using `igni:` commands will be stored in the `app/Http/Controller/Admin` directory. When generated the Igni CMS controllers are blank classes extending Igni's `AdminController`.
## Migrations
They will be stored in the default location - `database/migrations`. If you are creating an empty resource you must set the table name, columns and relationships on your own.
## Entities
### What are entities?
The resource entity is the main config file for its functionality. In it you can set which model/controller to use, desired actions, fields, columns and much more. The entity files are stored in `config/entities`.
### How to use them?
Here's an example entity config which defines a user management resource.
```php
return [
    'name' => 'User',
    'description' => 'User resource',
    'model' => config('auth.providers.users.model'),
    'controller' => \Despark\Cms\Http\Controllers\UsersController::class,
    'adminColumns' => [
        'name',
        'email',
        'Admin?' => 'is_admin',
    ],
    'actions' => ['edit', 'create', 'destroy'],
    'adminFormFields' => [
        'name' => [
            'type' => 'text',
            'label' => 'Name',
        ],
        'email' => [
            'type' => 'text',
            'label' => 'Email',
        ],
        'is_admin' => [
            'type' => 'checkbox',
            'label' => 'is_admin',
        ],
        'password' => [
            'type' => 'password',
            'label' => 'Password',
        ],
    ],
    'adminMenu' => [
        'user_management' => [
            'name' => 'User Management',
            'iconClass' => 'fa-users',
        ],
        'users' => [
            'name' => 'Users',
            'link' => 'user.index',
            'parent' => 'user_management',
        ],
    ],
];

```
#### name
You can set your page title, browser tab title and add button name in that item. (E.g. If you call it User, the button for creating a new item will be called "Add User").
#### model and controller
Define the used controller and model for your entity here.
#### adminColumns
Array defining which columns to show in your table at the listing page. You can also use relationships here.

_Example_

You have a relationship between a user and a car and you want to show the user's name and their car model in the users listing:
```php
'adminColumns' => [
    'name',
    'car model' => 'car.model',
],
```
Keep in mind that in the listing all 0 and 1 values are casted to No/Yes.
#### actions
You can also limit the available actions for a resource. By default they are set to all:
```php
'actions' => ['edit', 'create', 'destroy'],
```
#### adminFormFields
In that array you set all the fields to which the admins have access in the create/edit form. Don't forget to also set the fields as fillable in the model. For full listing of available field type please go to [Form fields](form-fields).
#### image_fields
Here you set the settings for all of your image fields. 
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
The `admin` array contains the settings for the thumbnail created for the CMS, which is shown when editing the resource. The available types of image manipulations are `resize`, `crop` and `fit`. In the `normal` array you should define the standard size of the images. If `retina_factor` is enabled in `config/ignicms.php` then you must upload images with size at least double the size that is entered in `normal` (E.g. for `column_name` in the example above you must upload an image with minimum 800x800px).
#### adminMenu
Here is the place where you set the menu items shown in the sidebar of the CMS. You can create normal or nested sidebar menu items. In order to create a nested sidebar list you need to use the `parent` key and state the name of the item you want to assign as parent. The `weight` column is not required. It is used to sort your items in a way that you prefer. In the example below, the User management item will be always on top of the sidebar items and it will have the following nested items: Users and Roles in that particular order. 

_Example_

Nested sidebar list

`config/entities/users.php`

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
 
`config/entities/roles.php`
 
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

`config/entities/users.php`

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
## Create Pages module
Use the command `php artisan igni:make:pages` to create all necessary files for manipulating Pages.

_Example_

```
    php artisan igni:make:pages
```
## Create Contacts module
If you want a command for creating a Contacts page resource, you should add our contacts module for IgniCMS. You can find full information about it [here](https://github.com/despark/igni-contact-us).

## Image rebuilding
You can rebuild your uploaded images `php artisan igni:images:rebuild`. If you want you can specify which resources to rebuild with the `--resources=*` switch.
_Example_

```
    php artisan igni:image:rebuild --resources App\\Test
```
You can exclude some resources with `--without=*`.

# Features info
## Form fields
### Checkbox
```php
'column_name' => [
    'type' => 'checkbox',
    'label' => 'I am a checkbox',
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
Keep in mind that the images relationship is polymorphic, so you don't have to make a column in your table. You just need to replace `column_name` with your desired one.
```php
'column_name' => [
    'type' => 'imageSingle',
    'label' => 'I am a image single upload',
],
```

### Password
```php
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

And here is how to define the field in the entity:
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

### Many to many select
This field type can be used for implementing tagging functionality. First, we need to make the many to many relationship between the Models. For this example we are using a User and Permission class.
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

Next we need to get the data for the Select. For example in the `app/Sources` directory, we can create `Permissions` class which gets the needed data:
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

Finally, we need to setup the field in the entity:
```php
'column_name[]' => [ // In this case permissions[]
    'type' => 'manyToManySelect',
    'label' => 'Permissions',
    'additionalClass' => 'select2-tags', // This is not required. Use it if you need some extra classes
    'sourceModel' => \App\Sources\Permissions::class,
    'relationMethod' => 'permissions', // The name of the relation
    'validateName' => 'permissions', // Which name to validate in the protected $rules array
    'selectedKey' => 'id', // Which key will be the value for the select
],
```

It also works with polymorphic relationships. You can find more info about this type of relationships in the [Laravel docs](https://laravel.com/docs/5.5/eloquent-relationships#many-to-many-polymorphic-relations)

### Custom
If you need to do a more customized functionality than the ones which are provided for you out of the box you can use a custom field implementation. To accomplish this you must create a Handler, Template and Factory, the place of these files depends on you. In the example below we've implemented a color picker and stored the files for it in `app/Fields`:
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

Our factory class will be stored in `app\Factories`:
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

Finally, let's define the field in the entity:
```php
'column_name' => [
    'type' => 'custom',
    'handler' => \App\Fields\Color::class,
    'template' => 'admin.color',
    'factory' => \App\Factories\ColorFactory::class,
    'label' => 'I am a custom field',
],
```
## Images
## Images
### Check if the resource has images
```php
    $model->hasImages($type = null);
```
### Get uploaded images
Here is how you can get your image for a specific resource:
```php
    $image = $model->getImages();
```
You can pass the id of the field given in the resource entity file as an argument. In that case the function will return the image associated with the given id.
```php
    $image = $model->getImages($fieldName);
```
You can also use
```php
    $image = $model->getImagesOfType($fieldName);
```
To display the image in your view you can use the following function:
```php
    {!! $image->toHtml('normal') !!}
```
where ```normal``` is the image thumbnail provided in the resource entity file.

Example resource entity file:
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

### Get image model
```php
    $model->getImageModel();
```

### Get image relation
```php
    $model->images();
```

### Get minimal dimensions for an image
```php
    $model->getMinDimensions($fieldName);
```
By default, the data is returned as an array. If you want it as string, you can set a second parameter as true. Also you can get only the minimal width or height:
``` php
    $model->getMinWidth($fieldName);
    $model->getMinHeight($fieldName);
```

### Get retina factor
```php
    $model->getRetinaFactor();
```

### Get required form images
```php
    $model->getRequiredImages();
```

### Get image fields and meta fields
```php
    $model->getImageFields();
```
```php
    $model->getImageMetaFields($fieldName);
```
```php
    $model->getImageMetaFieldsHtml($fieldName);
```

### Get upload directory
```php
    $model->getCurrentUploadDir();
```

### Creating a thumbnail
```php
    $model->createThumbnail($sourceImagePath, $thumbName, $newFileName, $width = null, $height = null, $resizeType = 'crop', $color = null
    );
```

### Set image model
```php
    $model->setImageModel($imageModel);
```

### Set minimal dimensions
```php
    $model->setMinDimensions($field, $minDimensions);
```

### Set retina factor
```php
    $model->setRetinaFactor($factor);
```

## Resetting passwords
In order to use the reset password functionality, you must fill in the MAIL and APP settings in your `.env` file or modify the defaults in `config/app.php` and `config/mail.php`.

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
After modifying the APP_URL, the URLs in the email sent to the user will be working as expecred!

If you want to change the standard email template, you can find out how in the [Laravel docs](https://laravel.com/docs/5.4/mail#customizing-the-components).

## Localization
IgniCMS provides internationalization out of the box through the i18n package. You can find full information about it [here](https://github.com/despark/laravel-db-i18n).

## Change CMS logo
You can change the CMS logo in `config/ignicms.php`.
  ```php
   ...
    // For best performance the image must be with width 234px
    'logo' => 'images/logo.png',
   ...
  ```

## Brute force protection
If someone unsuccessfully tries to login with a certain email 5 times in a row, this account will be blocked for 15 minutes.

