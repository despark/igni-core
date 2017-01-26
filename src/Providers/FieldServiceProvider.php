<?php

namespace Despark\Cms\Providers;

use Despark\Cms\Fields;
use Despark\Cms\Fields\Gallery;
use Illuminate\Support\ServiceProvider;

/**
 * Class FieldServiceProvider.
 */
class FieldServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @var array
     */
    protected $fields = [
        'text' => Fields\Text::class,
        'select' => Fields\Select::class,
        'select2' => Fields\Select2::class,
        'custom' => Fields\Custom::class,
    ];

    /**
     *
     */
    public function register()
    {
        foreach ($this->getFields() as $field => $class) {
            $this->app->bind($field.'_field', function ($app, $params) use ($class) {
                return new $class($params['model'], $params['field'], $params['options'], $params['element_name']);
            });
        }
    }

    /**
     * @return array
     */
    public function provides()
    {
        $fields = $this->getFields();

        return array_map(function ($field) {
            return $field.'_field';
        }, array_keys($fields));
    }

    /**
     * Get all registered fields.
     * @return array
     */
    public function getFields()
    {
        // TODO add some way to alter them by other modules.
        return $this->fields;
    }
}
