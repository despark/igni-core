<?php

namespace Despark\Cms\Providers;

use Despark\Cms\Models\Image;
use Despark\Cms\Fields\Factory;
use Despark\Cms\Helpers\FileHelper;
use Despark\Cms\Assets\AssetManager;
use Illuminate\Support\ServiceProvider;
use Despark\Cms\Contracts\ImageContract;
use Despark\Cms\Contracts\AssetsContract;

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
        $this->app->bind(ImageContract::class, function ($app) {
            $class = $app['config']['ignicms']['images']['model'];

            if (class_exists($class)) {
                return new $class();
            }
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

        $this->app->bind(\Flow\File::class, function () {
            $config = new \Flow\Config([
                'tempDir' => FileHelper::getTempDirectory(),
            ]);

            return new \Flow\File($config);
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
