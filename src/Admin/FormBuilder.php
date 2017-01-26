<?php

namespace Despark\Cms\Admin;

use Despark\Cms\Contracts\SourceModel;
use Despark\Cms\Fields\Field;
use Despark\Cms\Models\AdminModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FormBuilder
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
    private $options;

    /**
     * @var array
     */
    protected $rendered = [];

    /**
     * @param string $view
     *
     * @return \Illuminate\View\View
     * @todo deprecate this
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
     * @return string
     */
    public function render(Model $model, array $fields)
    {
        $html = '';
        foreach ($fields as $field => $options) {
            // Check if field is not already rendered
            if (! $this->isRendered($model, $field)) {
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
     * @param Model     $model
     * @param string    $field
     * @param           $options
     * @param null      $elementName
     * @return \Illuminate\View\View|string
     */
    public function field($model, $field, $options, $elementName = null)
    {
        if (is_null($elementName)) {
            $elementName = isset($options['name']) ? $options['name'] : $field;
        }
        $fieldProvider = $options['type'].'_field';
        if (\App::bound($fieldProvider)) {
            return \App::make($fieldProvider, [
                'model' => $model,
                'field' => $field,
                'options' => $options,
                // TODO element name should be respected by the Field class
                'element_name' => $elementName,
            ]);
        } else {
            // TODO WE NEED TO DEPRECATE FIELDS WITHOUT CLASSES
            $this->model = $model;
            $this->field = $field;
            // Check for source model
            if (isset($options['sourceModel']) && is_a($options['sourceModel'], SourceModel::class, true)) {
                $this->sourceModel = app($options['sourceModel']);
            }
            if (! isset($options['class'])) {
                $options['class'] = '';
            }
            //Check if we don't have validation rules
            if (isset($options['validation'])) {
                foreach (explode('|', $options['validation']) as $rule) {
                    // For now we allow only rules without , check validation.js
                    if (strstr($rule, ',') === false) {
                        $options['class'] .= ' validate-'.$rule;
                    }
                }
            }

            $this->options = $options;
            $this->elementName = $elementName;

            return $this->renderInput($this->options['type']);
        }
    }

    /**
     * @param Model $model
     * @param       $field
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
     * @return FormBuilder
     */
    public function setRendered($rendered)
    {
        $this->rendered = $rendered;

        return $this;
    }


}
