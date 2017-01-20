<?php


namespace Despark\Cms\Resource;

use Despark\Cms\Http\Controllers\ResourceController;


/**
 * Class ResourceManager.
 */
class ResourceManager
{

    /**
     * @var
     */
    protected $resources;

    protected $routeMethods = ['index', 'create', 'show', 'edit', 'store', 'destroy', 'update'];

    /**
     *
     */
    public function load()
    {
        // Get all configs
        $files = \File::allFiles(config('ignicms.paths.resources', config_path('resources')));

        foreach ($files as $file) {
            $resource = str_slug(pathinfo($file, PATHINFO_FILENAME), '_');
            $this->resources[$resource] = call_user_func(function () use ($file) {
                return include $file;
            });
        }
    }

    /**
     * @param      $resource
     * @param null $key
     * @param null $default
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
     * @return mixed
     */
    public function all()
    {
        return $this->resources;
    }


    /**
     * Generates all the needed routes for resources
     */
    public function routes()
    {
        foreach ($this->all() as $resource => $config) {

            $availableMethods = $this->routeMethods;
            // Get the implementing controller and check for rewritten routes
            $methods = array_intersect(get_class_methods($config['controller']), $availableMethods);

            if (! empty($methods)) {
                // If all routes are rewritten we use the config one
                if (count($methods) == count($availableMethods)) {
                    \Route::resource($resource, $config['controller'], ['names' => build_resource_backport($resource)]);
                } else {
                    \Route::resource($resource, $config['controller'],
                        [
                            'only' => $methods,
                            'names' => build_resource_backport($resource, $methods),
                        ]);
                    \Route::resource($resource, ResourceController::class, [
                        'except' => $methods,
                        'names' => build_resource_backport($resource, [], $methods),
                    ]);
                }
            } else {
                \Route::resource($resource, ResourceController::class);
            }

        }
    }

}