<?php


namespace Despark\Cms\Providers;


use Despark\Cms\Assets\AssetManager;
use Despark\Cms\Contracts\AssetsContract;
use Despark\Cms\Contracts\ImageContract;
use Despark\Cms\Models\Image;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Image contract implementation
         */
        $this->app->bind(ImageContract::class, function ($app, $attributes = []) {
            return new Image($attributes);
        });

        /*
         * Assets manager
         */
        $this->app->singleton(AssetsContract::class, AssetManager::class);
//
//        /*
//         * Form Builder singleton
//         */
//        $this->app->singleton(FormBuilder::class, FormBuilder::class);
    }

    public function provides()
    {
        return [ImageContract::class, AssetsContract::class];
    }
}