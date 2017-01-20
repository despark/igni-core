<?php

namespace Despark\Cms\Providers;

use Despark\Cms\Assets\AssetManager;
use Despark\Cms\Contracts\AssetsContract;
use Despark\Cms\Contracts\ImageContract;
use Despark\Cms\Illuminate\View\View;
use Despark\Cms\Models\Image;
use File;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator;

class CoreServiceProvider extends ServiceProvider
{
    use AppNamespaceDetectorTrait;

    /**
     * Artisan commands.
     *
     * @var array
     */
    protected $commands = [
        \Despark\Cms\Console\Commands\InstallCommand::class,
        \Despark\Cms\Console\Commands\Admin\ResourceCommand::class,
        \Despark\Cms\Console\Commands\File\ClearTemp::class,
        \Despark\Cms\Console\Commands\Image\Rebuild::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot(Router $router)
    {
        // Schedule commands after boot
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('igni:file:clear')->weeklyOn(6);
        });

        // NB:Version dependent
        // Routes
        $router->group(['namespace' => 'Despark\Cms\Http\Controllers'], function ($router) {
            require __DIR__.'/../routes/web.php';
        });

        // Add our resource routes
        $router->group(['prefix' => 'admin', 'middleware' => 'auth.admin'], function ($router) {
            require __DIR__.'/../routes/resources.php';
        });
        
        // Register Assets
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'ignicms');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'lang');

        // Register config
        $this->mergeConfigFrom(__DIR__.'/../../config/ignicms.php', 'ignicms');
        $this->mergeConfigFrom(__DIR__.'/../../config/admin/sidebar.php', 'admin.sidebar');
        $this->mergeConfigFrom(__DIR__.'/../../config/resources/user.php', 'resources.user');

        // Register the application commands
        $this->commands($this->commands);

        // Publish the Resources

        // TODO VERSION DEPENDANT
        // Migrations
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'migrations');
        // Configs
        $this->publishes([
            __DIR__.'/../../config/' => config_path(),
        ], 'config');
        // Resources
        $this->publishes([
            __DIR__.'/../../resources/assets' => base_path('/resources/assets'),
            __DIR__.'/../../resources/lang' => base_path('/resources/lang'),
            __DIR__.'/../../resources/icomoon.json' => base_path('/resources/icomoon.json'),
        ], 'resources');

        // Gulp
        $this->publishes([
            __DIR__.'/../../gulp/' => base_path('/gulp'),
        ], 'gulp');
        // Public
        $this->publishes([
            __DIR__.'/../../public/' => public_path(),
        ], 'public');

        $this->publishes([
            __DIR__.'/../../package.json' => base_path('package.json'),
            __DIR__.'/../../bower.json' => base_path('bower.json'),
            __DIR__.'/../../.bowerrc' => base_path('.bowerrc'),
            __DIR__.'/../../.babelrc' => base_path('.babelrc'),
            __DIR__.'/../../.eslintrc' => base_path('.eslintrc'),
            __DIR__.'/../../.editorconfig' => base_path('.editorconfig'),
            __DIR__.'/../../gulpfile.js' => base_path('gulpfile.js'),
        ], 'fe');

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
        // $this->app->register('Roumen\Sitemap\SitemapServiceProvider');
        $this->app->register(\Rutorika\Sortable\SortableServiceProvider::class);
        //        $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');

        /*
         * Image contract implementation
         */
        $this->app->bind(ImageContract::class, function ($app, $attributes = []) {
            return new Image($attributes);
        });

        /*
         * Create aliases for the dependency.
         */
        $loader = AliasLoader::getInstance();
        $loader->alias('Form', 'Collective\Html\FormFacade');
        $loader->alias('Html', 'Collective\Html\HtmlFacade');
        // Todo Core considerations
        $loader->alias('Image', 'Intervention\Image\Facades\Image');

        /*
         * Assets manager
         */
        $this->app->singleton(AssetsContract::class, AssetManager::class);

        /*
         * Switch View implementation
         */
        $this->app->bind(ViewContract::class, View::class);

        $this->registerFactory();
    }

    /**
     * Register the view environment.
     *
     * @return void
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
     * @todo Create validators with classes.
     */
    public function addValidators()
    {
        \Validator::extendImplicit('gallery_required', function ($attribute, $value, $parameters, $validator) {
            /* @var Validator $validator */

            if (class_exists($parameters[0])) {
                $model = new $parameters[0];
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
