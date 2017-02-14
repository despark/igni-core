<?php

namespace Despark\Cms\Providers;

use Despark\Cms\Fields\Factory;
use Illuminate\Support\ServiceProvider;
use Despark\Cms\Fields\Contracts\Factory as FactoryContract;

/**
 * Class FieldServiceProvider.
 */
class FieldServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    // protected $defer = true;

    public function register()
    {
        $this->app->bind(FactoryContract::class, function ($app) {
            return new Factory($app);
        });
    }
}
