<?php

namespace Despark\Cms\Fields\Facades;

class Field extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'field';
    }
}
