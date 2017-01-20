<?php


namespace Despark\Cms\Providers;


use Despark\Cms\Resource\ResourceManager;
use Illuminate\Support\ServiceProvider;

class ResourceServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Despark\Cms\Resource\ResourceManager::class, function ($app) {
            // Bring it up
            $resourceManager = new ResourceManager();
            $resourceManager->load();

            return $resourceManager;
        });
    }
}