<?php


namespace Despark\Cms\Providers;


use Despark\Cms\Admin\Sidebar;
use Despark\Cms\Resource\ResourceManager;
use Illuminate\Support\ServiceProvider;

/**
 * Class ResourceServiceProvider.
 */
class ResourceServiceProvider extends ServiceProvider
{

    /**
     * @var bool
     */
    protected $defer = true;

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

        $this->app->singleton(Sidebar::class, function ($app) {
            $resourceManager = $app->make(\Despark\Cms\Resource\ResourceManager::class);

            return new Sidebar($resourceManager);
        });

    }

    /**
     * @return array
     */
    public function provides()
    {
        return [\Despark\Cms\Resource\ResourceManager::class, Sidebar::class];
    }
}