<?php

namespace Despark\Cms\Admin;

use Despark\Cms\Fields\Field;
use Despark\Cms\Models\AdminModel;
use Despark\Cms\Contracts\SourceModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FormBuilder.
 */
class FormBuilder
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
     * @var SourceModel
     */
    protected $sourceModel;
    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    protected $rendered = [];

    /**
     * @param string $view
     *
     * @return \Illuminate\View\View
     *
     * @deprecated
     */
    public function renderInput($view)
    {
        // First check if there isn't a model view.
        $viewName = 'ignicms::admin.formElements.'.$view;
        if ($this->model instanceof AdminModel && $identifier = $this->model->getIdentifier()) {
            // First check if there is a rewrite on specific field type
            $field = str_slug($this->field);
            if (\View::exists('resources.'.$identifier.'.formElements.'.$field)) {
                $viewName = 'resources.'.$identifier.'.formElements.'.$field;
            } elseif (\View::exists('resources.'.$identifier.'.formElements.'.$view)) {
                $viewName = 'resources.'.$identifier.'.formElements.'.$view;
            }
        }

        return view($viewName, [
            'record' => $this->model,
            'fieldName' => $this->field,
            'elementName' => $this->elementName,
            'options' => $this->options,
            'sourceModel' => $this->sourceModel,
        ]);
    }

    /**
     * @param Model $model
     * @param array $fields
     *
     * @return string
     */
    public function render(Model $model, array $fields)
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

    /**
     * @param Model       $model
     * @param string      $field
     * @param             $options
     * @param string|null $elementName
     *
     * @return \Illuminate\View\View|string
     */
    public function field($model, $fieldName, $options, $elementName = null)
    {
        return \Field::make($model, $fieldName, $options);
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
