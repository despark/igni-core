<?php

namespace Despark\Cms\Admin;

use Form;
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

    public function field($model, $fieldName, $options, $elementName = null)
    {
        $field = $fieldName;
        $options['elementName'] = $elementName;

        $data = compact('options', 'field', 'model');

        return \Field::make($data);

        // return \Field::make($model, $fieldName, $options);
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
     * @return Form
     */
    public function setRendered($rendered)
    {
        $this->rendered = $rendered;

        return $this;
    }
}
