<?php

namespace Despark\Cms\Events\EntityManager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AfterFieldsMake
{
    /**
     * @var array
     */
    public $fieldInstances;

    /**
     * @var Model
     */
    public $model;

    /**
     * AfterFieldsMake constructor.
     *
     * @param Collection $fieldInstances
     * @param Model      $model
     */
    public function __construct(Collection $fieldInstances, Model $model)
    {
        $this->fieldInstances = $fieldInstances;
        $this->model = $model;
    }
}
