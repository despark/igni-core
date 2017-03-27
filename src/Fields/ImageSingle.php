<?php

namespace Despark\Cms\Fields;

class ImageSingle extends Field
{
    protected $model;

    public function __construct($fieldName, array $options, $value = null)
    {
        $options['attributes']['id'] = $fieldName;
        
        parent::__construct($fieldName, $options, $value);
    }

    /**
     * Sets the value of model.
     *
     * @param mixed $model the model
     *
     * @return self
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Gets the value of model.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }
}
