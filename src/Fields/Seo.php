<?php

namespace Despark\Cms\Fields;

class Seo extends Field
{
    protected $model;

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

    public function getRoute()
    {
        $actionVerb = $this->options['actionVerb'] ?? 'show';

        return route(strtolower(class_basename($this->model)).'.'.$actionVerb, '');
    }

    public function getSlug()
    {
        return $this->model->slug;
    }
}
