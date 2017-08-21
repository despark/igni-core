<?php

namespace Despark\Cms\Providers;

use Despark\Cms\Illuminate\View\View;
use File;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator;

class IgniServiceProvider extends ServiceProvider
{
    use DetectsApplicationNamespace;

    /**
     * Artisan commands.
     *
     * @var array
     */
    protected $commands = [
        \Despark\Cms\Console\Commands\InstallCommand::class,
        \Despark\Cms\Console\Commands\ResourceCommand::class,
        \Despark\Cms\Console\Commands\PagesResourceCommand::class,
        \Despark\Cms\Console\Commands\Image\Rebuild::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot(Router $router)
    {
        // Routes
        $router->group(['namespace' => 'Despark\Cms\Http\Controllers', 'middleware' => ['web']], function ($router) {
            require __DIR__.'/../routes/web.php';
        });

        // Add our resource routes
        $router->group(['prefix' => 'admin', 'middleware' => ['web', 'auth.admin']], function ($router) {
            require __DIR__.'/../routes/resources.php';
        });

        // Register Assets
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'ignicms');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'ignicms');

        // Register config
        $this->mergeConfigFrom(__DIR__.'/../../config/ignicms.php', 'ignicms');
        $this->mergeConfigFrom(__DIR__.'/../../config/entities/user.php', 'resources.user');

        // Register the application commands
        $this->commands($this->commands);

        // Publish the Resources

        // Migrations
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'migrations');
        // Configs
        $this->publishes([
            __DIR__.'/../../config/' => config_path(),
        ], 'configs');
        // Plugins
        $this->publishes([
            __DIR__.'/../../public/admin_assets/plugins' => base_path('/public/admin_assets/plugins'),
            __DIR__.'/../../public/plugins/' => base_path('/public/plugins'),
        ], 'plugins');
        // Resources
        $this->publishes([
            __DIR__.'/../../resources/assets' => base_path('/resources/assets'),
            __DIR__.'/../../resources/lang' => base_path('/resources/lang/vendor/ignicms'),
            __DIR__.'/../../resources/icomoon.json' => base_path('/resources/icomoon.json'),
        ], 'resources');

        $this->publishes([
            __DIR__.'/../../gulp/' => base_path('/gulp'),
            __DIR__.'/../../package.json' => base_path('package.json'),
            __DIR__.'/../../bower.json' => base_path('bower.json'),
            __DIR__.'/../../.bowerrc' => base_path('.bowerrc'),
            __DIR__.'/../../.babelrc' => base_path('.babelrc'),
            __DIR__.'/../../.eslintrc' => base_path('.eslintrc'),
            __DIR__.'/../../.editorconfig' => base_path('.editorconfig'),
            __DIR__.'/../../gulpfile.js' => base_path('gulpfile.js'),
        ], 'igni-frontend');

        $configPaths = config('ignicms.paths');
        if ($configPaths) {
            foreach ($configPaths as $key => $path) {
                if (! is_dir($path)) {
                    File::makeDirectory($path, 0755, true);
                }
            }
        }

        $this->addValidators();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        /*
         * Register the service provider for the dependency.
         */
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\Intervention\Image\ImageServiceProvider::class);
        $this->app->register(\Cviebrock\EloquentSluggable\ServiceProvider::class);
        $this->app->register(\Rutorika\Sortable\SortableServiceProvider::class);

        /*
         * Create aliases for the dependency.
         */
        $loader = AliasLoader::getInstance();
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('Html', \Collective\Html\HtmlFacade::class);
        $loader->alias('Entity', \Despark\Cms\Resource\Facades\EntityManager::class);
        // Todo Core considerations
        $loader->alias('Image', \Intervention\Image\Facades\Image::class);
        $loader->alias('Field', \Despark\Cms\Fields\Facades\Field::class);

        /*
         * Switch View implementation
         */
        $this->app->bind(ViewContract::class, View::class);

        $this->registerFactory();
    }

    /**
     * Register the view environment.
     */
    public function registerFactory()
    {
        $this->app->singleton('view', function ($app) {
            // Next we need to grab the engine resolver instance that will be used by the
            // environment. The resolver will be used by an environment to get each of
            // the various engine implementations such as plain PHP or Blade engine.
            $resolver = $app['view.engine.resolver'];

            $finder = $app['view.finder'];

            $env = new \Despark\Cms\Illuminate\View\Factory($resolver, $finder, $app['events']);

            // We will also set the container instance on this view environment since the
            // view composers may be classes registered in the container, which allows
            // for great testable, flexible composers for the application developer.
            $env->setContainer($app);

            $env->share('app', $app);

            return $env;
        });
    }

    /**
     * Add custom validators.
     *
     * @todo Create validators with classes
     */
    public function addValidators()
    {
        \Validator::extendImplicit('gallery_required', function ($attribute, $value, $parameters, $validator) {
            /* @var Validator $validator */

            if (class_exists($parameters[0])) {
                $model = new $parameters[0]();
                // we need to build the model
                $model->fill(request()->all());
                if (method_exists($model, 'getRequiredImages')) {
                    return $model->hasFieldValue($attribute);
                }
            }

            return false;
        }, trans('validation.required'));
    }
}
