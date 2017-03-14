<?php

namespace Despark\Cms\Resource;

use Despark\Cms\Admin\Form;
use Despark\Cms\Admin\FormBuilder;
use Despark\Cms\Fields\Facades\Field;
use Despark\Cms\Fields\Hidden;
use Despark\Cms\Fields\Translations;
use Despark\Cms\Http\Controllers\EntityController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

/**
 * Class EntityManager.
 */
class EntityManager
{
    /**
     * @var
     */
    protected $resources = [];

    /**
     * @var array
     */
    protected $routeMethods = ['index', 'create', 'show', 'edit', 'store', 'destroy', 'update'];

    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * EntityManager constructor.
     *
     * @param FormBuilder $formBuilder
     */
    public function __construct(FormBuilder $formBuilder, Form $form)
    {
        $this->formBuilder = $formBuilder;
        $this->form = $form;
    }

    /**
     * Load the resource manager.
     */
    public function load()
    {
        // Get all configs
        if (\File::isDirectory(config_path('entities'))) {
            $files = \File::allFiles(config('ignicms.paths.entities', config_path('entities')));

            foreach ($files as $file) {
                $resource = str_slug(pathinfo($file, PATHINFO_FILENAME), '_');
                $resourceConfig = call_user_func(function () use ($file, $resource) {
                    $array = include $file;
                    if (is_array($array)) {
                        return array_merge(['id' => $resource], $array);
                    }

                    return null;
                });
                if ($resourceConfig) {
                    $this->resources[$resourceConfig['id']] = $resourceConfig;
                }
            }
        }

        // Add igni default resources
        $localFiles = \File::allFiles(__DIR__.'/../../config/entities');
        foreach ($localFiles as $file) {
            $resource = str_slug(pathinfo($file, PATHINFO_FILENAME), '_');
            if (!isset($this->resources[$resource])) {
                $resourceConfig = call_user_func(function () use ($file, $resource) {
                    $array = include $file;
                    if (is_array($array)) {
                        return array_merge(['id' => $resource], $array);
                    }

                    return null;
                });
                if ($resourceConfig) {
                    if (!isset($this->resources[$resourceConfig['id']])) {
                        // We need to make sure we don't override existing sidebar items
                        if (isset($resourceConfig['adminMenu'])) {
                            foreach ($this->resources as $existingResource) {
                                if (isset($existingResource['adminMenu'])) {
                                    $existingMenuItems = array_keys($existingResource['adminMenu']);
                                    $candidateMenuItems = array_keys($resourceConfig['adminMenu']);
                                    $nonIntersecting = array_diff($candidateMenuItems, $existingMenuItems);
                                    if (empty($nonIntersecting)) {
                                        unset($resourceConfig['adminMenu']);
                                    } else {
                                        $resourceConfig['adminMenu'] = array_only($resourceConfig['adminMenu'],
                                            $nonIntersecting);
                                    }
                                }
                            }
                        }

                        $this->resources[$resourceConfig['id']] = $resourceConfig;
                    }
                }
            }
        }
    }

    /**
     * @param      $resource
     * @param null $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function get($resource, $key = null, $default = null)
    {
        $resource = str_slug($resource, '_');
        if (isset($this->resources[$resource])) {
            if (is_null($key)) {
                return $this->resources[$resource];
            }
            $data = $this->resources[$resource];

            return array_get($data, $key, $default);
        }

        return $default;
    }

    /**
     * @param Model $model
     *
     * @return array
     */
    public function getByModel(Model $model)
    {
        $class = get_class($model);
        foreach ($this->all() as $item) {
            if ($item['model'] === $class) {
                return $item;
            }
        }
    }

    /**
     * @param Controller $controller
     *
     * @return mixed
     */
    public function getByController(Controller $controller)
    {
        $class = get_class($controller);

        if ($class == EntityController::class) {
            // If it's resource controller we'll try to get the resource by route
            return $this->getByRoute();
        } else {
            foreach ($this->all() as $item) {
                if ($item['controller'] === $class) {
                    return $item;
                }
            }
        }
    }

    /**
     * @return null|string
     */
    public function getByRoute()
    {
        $routeName = \Route::currentRouteName();
        $routeParts = explode('.', $routeName);
        $resourceName = reset($routeParts);

        return $this->getById($resourceName);
    }

    /**
     * @param $id
     *
     * @return null|string
     */
    public function getById($id)
    {
        return isset($this->resources[$id]) ? $this->resources[$id] : null;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->resources;
    }

    /**
     * Generates all the needed routes for resources.
     */
    public function routes()
    {
        foreach ($this->all() as $resource => $config) {
            $availableMethods = $this->routeMethods;
            $classMethods = get_class_methods($config['controller']) ?? [];
            // Get the implementing controller and check for rewritten routes
            $methods = array_intersect($classMethods, $availableMethods);

            if (!empty($methods)) {
                // If all routes are rewritten we use the config one
                if (count($methods) == count($availableMethods)) {
                    \Route::resource($resource, $config['controller'], [
                        'names' => build_resource_backport($resource),
                    ]);
                } else {
                    \Route::resource($resource, $config['controller'], [
                        'only' => $methods,
                        'names' => build_resource_backport($resource, $methods),
                    ]);

                    \Route::resource($resource, EntityController::class, [
                        'except' => $methods,
                        'names' => build_resource_backport($resource, [], $methods),
                    ]);
                }
            } else {
                \Route::resource($resource, EntityController::class);
            }

            $this->setRoutes($resource, build_resource_backport($resource));
        }
    }

    public function getModelRoutes(Model $model)
    {
        $resource = $this->getByModel($model)['id'];

        return array_get($this->getRoutes(), $resource);
    }

    public function getRouteName(Model $model, string $action)
    {
        return array_get($this->getModelRoutes($model), $action);
    }

    /**
     * Renders entire form.
     *
     * @param Model $model
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getForm(Model $model)
    {
        $method = $model->exists ? 'PUT' : 'POST';
        $actionVerb = $model->exists ? 'update' : 'store';
        $attributes = $model->getKey() ? ['id' => $model->getKey()] : [];
        $action = route($this->getRouteName($model, $actionVerb), $attributes);
        $translatable = ($model instanceof \Despark\LaravelDbLocalization\Contracts\Translatable) ? $model->getTranslatable() : null;
        $locale = app('request')->get('locale', \App::getLocale());

        $fields = $this->getFields($model);
        $fieldInstances = [];

        if ($translatable) 
        {
            foreach ($translatable as $translate)
            {
                $fieldInstances[] = new Translations($translate, [], $locale);
            }
            $fieldInstances[] = new Hidden('locale', [], $locale);
        }
        else
        {

        }

        foreach ($fields as $fieldName => $options) {
            if ($translatable) {
                $value = $model->getTranslation();
            }
            else {
                $value = $model->getOriginal($fieldName);
            }
            $fieldInstances[] = \Field::make($fieldName, $options, $value);
        }
        
        return $this->form->make([
            'action' => $action,
            'method' => $method,
            'fields' => $fieldInstances,
        ]);
    }

    /**
     * Renders single field.
     *
     * @param Model $model
     * @param       $fieldId
     *
     * @return \Illuminate\View\View|string
     *
     * @throws \Exception
     */
    public function renderField(Model $model, $fieldId)
    {
        $fields = $this->getFields($model);
        foreach ($fields as $field => $config) {
            if ($fieldId == $field) {
                return $this->formBuilder->field($model, $field, $config);
            }
        }
    }

    /**
     * @param Model $model
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getFields(Model $model)
    {
        $resource = $this->getByModel($model);
        if (!$resource) {
            throw new \Exception('Model ('.get_class($model).') is missing resource configuration');
        }
        if (isset($resource['adminFormFields']) && is_array($resource['adminFormFields'])) {
            return $resource['adminFormFields'];
        }
    }

    /**
     * @param Model $model
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getFormTemplate(Model $model)
    {
        $resourceConfig = $this->getByModel($model);
        if ($resourceConfig && isset($resourceConfig['formTemplate'])) {
            if (!\View::exists($resourceConfig['formTemplate'])) {
                throw new \Exception('View template '.$resourceConfig['formTemplate'].' does not exist');
            }

            return $resourceConfig['formTemplate'];
        }

        return config('ignicms.defaultFormView');
    }

    /**
     * @return FormBuilder
     */
    public function getFormBuilder()
    {
        return $this->formBuilder;
    }

    /**
     * Gets the value of routes.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Sets the value of routes.
     *
     * @param array $routes the routes
     *
     * @return self
     */
    public function setRoutes($resource, array $routes)
    {
        $this->routes[$resource] = $routes;

        return $this;
    }
}
