<?php

namespace Despark\Cms\Admin;

use Illuminate\Database\Eloquent\Model;
use Despark\Cms\Fields\Contracts\Factory as FactoryContract;

/**
 * Class Form.
 */
class Form
{
    /**
     * @var string
     */
    protected $elementName;
    /**
     * @var Model
     */
    protected $model;
    /**
     * @var string
     */
    protected $field;
    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    protected $rendered = [];

    /**
     * @param Model $model
     * @param array $fields
     *
     * @return string
     */
    public function getRenderedForm(Model $model, array $fields)
    {
        $html = '';
        foreach ($fields as $field => $options) {
            // Check if field is not already rendered
            if (!$this->isRendered($model, $field)) {
                $elementName = isset($options['name']) ? $options['name'] : $field;
                $fieldInstance = $this->field($model, $field, $options, $elementName);
                if ($fieldInstance instanceof Field) {
                    // We don't render fields marked as hidden
                    if ($fieldInstance->hidden == true) {
                        continue;
                    }
                }

                $this->rendered[get_class($model)][] = $field;
                $html .= $fieldInstance;
            }
        }

        return $html;
    }

    public function field($model, $fieldName, $options, $elementName = null)
    {
        return app(FactoryContract::class)->make($model, $fieldName, $options);
    }

    /**
     * @param Model $model
     * @param       $field
     *
     * @return bool
     */
    public function isRendered(Model $model, $field)
    {
        $modelClass = get_class($model);
        if (isset($this->rendered[$modelClass])) {
            return in_array($field, $this->rendered[$modelClass]);
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRendered()
    {
        return $this->rendered;
    }

    /**
     * @param array $rendered
     *
     * @return FormBuilder
     */
    public function setRendered($rendered)
    {
        $this->rendered = $rendered;

        return $this;
    }
}
