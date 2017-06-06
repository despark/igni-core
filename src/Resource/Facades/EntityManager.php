<?php

namespace Despark\Cms\Resource\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

class EntityManager extends BaseFacade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return \Despark\Cms\Resource\EntityManager::class;
    }
}
