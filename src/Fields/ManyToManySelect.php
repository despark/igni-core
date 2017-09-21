<?php

namespace Despark\Cms\Fields;

use Despark\Cms\Contracts\SourceModel;

class ManyToManySelect extends Field
{
    /**
     * @var SourceModel
     */
    protected $sourceModel;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @return array
     */
    public function getSelectOptions()
    {
        return $this->getSourceModel()->toOptionsArray();
    }

    /**
     * @return SourceModel
     *
     * @throws \Exception
     */
    public function getSourceModel()
    {
        if (! isset($this->sourceModel) && ($sourceModel = $this->getOptions('sourceModel'))) {
            if (class_exists($sourceModel)) {
                $this->sourceModel = new $sourceModel();
                if (! $this->sourceModel instanceof SourceModel) {
                    throw new \Exception('Source model for field '.$this->getFieldName().' must implement '.SourceModel::class);
                }
            } else {
                throw new \Exception('Source model is missing for field '.$this->getFieldName());
            }
        }

        return $this->sourceModel;
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

    /**
     * Gets the value of the relation method.
     *
     * @return mixed
     */
    public function getRelationMethod()
    {
        $relationMethod = $this->getOptions('relationMethod');

        return $this->model->$relationMethod;
    }
}
