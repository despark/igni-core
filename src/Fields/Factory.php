<?php

namespace Despark\Cms\Fields;

use Despark\Cms\Fields\Contracts\Factory as FactoryContract;
use Illuminate\Contracts\Container\Container;

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
        'textarea' => Textarea::class,
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

    public function make($field, array $options, $value = null)
    {
        $type = $options['type'];
        $instance = new $this->fields[$type]($field, $options, $value);
        if ($instance instanceof Field) {
            $instance->setFieldType($type);
        } else {
            throw new \Exception($this->fields[$type].' must be instance of '.Field::class);
        }

        return $instance;
    }

    public function extend($type, string $field)
    {
        $this->fields[$type] = $field;
    }
}
