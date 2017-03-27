<?php

namespace Despark\Cms\Providers;

use Despark\Cms\Assets\AssetManager;
use Despark\Cms\Contracts\AssetsContract;
use Despark\Cms\Contracts\ImageContract;
use Despark\Cms\Fields\Factory;
use Despark\Cms\Models\Image;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the service provider.
     */
    public function register()
    {
        /*
         * Image contract implementation
         */
        $this->app->bind(ImageContract::class, function ($app, $attributes = []) {
            return new Image();
        });

        /*
         * Route Middleware
         */
        $this->app->singleton('auth.admin', config('ignicms.auth.admin'));

        /*
         * Assets manager
         */
        $this->app->singleton(AssetsContract::class, AssetManager::class);

        $this->app->singleton('field', function ($app) {
            return new Factory($app);
        });
    }

    public function provides()
    {
        return [
            ImageContract::class,
            AssetsContract::class,
            'field',
            'auth.admin',
        ];
    }
}
