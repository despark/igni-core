<?php


namespace Despark\Cms\Providers;


use Despark\Cms\Admin\FormBuilder;
use Despark\Cms\Admin\Sidebar;
use Despark\Cms\Resource\EntityManager;
use Illuminate\Support\ServiceProvider;

/**
 * Class ResourceServiceProvider.
 */
class EntityServiceProvider extends ServiceProvider
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
        $this->app->singleton(\Despark\Cms\Resource\EntityManager::class, function ($app) {
            // Bring it up
            $formBuilder = new FormBuilder();
            $entityManager = new EntityManager($formBuilder);
            $entityManager->load();

            return $entityManager;
        });

        $this->app->singleton(Sidebar::class, function ($app) {
            $entityManager = $app->make(\Despark\Cms\Resource\EntityManager::class);

            return new Sidebar($entityManager);
        });

    }

    /**
     * @return array
     */
    public function provides()
    {
        return [\Despark\Cms\Resource\EntityManager::class, Sidebar::class];
    }
}