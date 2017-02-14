<?php

namespace Despark\Cms\Fields;

use Despark\Cms\Models\AdminModel;
use Illuminate\Contracts\Container\Container;
use Despark\Cms\Fields\Contracts\Factory as FactoryContract;

class Factory implements FactoryContract
{
    /**
     * @var array
     */
    protected $fields = [
        'text' => Text::class,
        'select' => Select::class,
        'select2' => Select2::class,
        'custom' => Custom::class,
        'date' => DateTime::class,
        'datetime' => DateTime::class,
        'checkbox' => Checkbox::class,
        'password' => Password::class,
    ];

    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    public function __construct(Container $container = null)
    {
        $this->container = $container;
    }

    public function make(AdminModel $model, $field, array $options)
    {
        $type = $options['type'];
        $instance = new $this->fields[$type]($model, $field, $options);
        if ($instance instanceof Field) {
            $instance->setFieldType($type);
        } else {
            throw new \Exception($this->fields[$type].' must be instance of '.Field::class);
        }

        return $instance;
    }
}
