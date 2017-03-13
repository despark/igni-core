<?php

namespace Despark\Cms\Admin;

use Despark\Cms\Fields\Field;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Form.
 */
class Form
{
    /**
     * @var array
     */
    protected $fields = [];
    /**
     * @var string
     */
    protected $action;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var string
     */
    protected $role = 'form';
    /**
     * @var array
     */
    protected $attributes = [];
    /**
     * @var string
     */
    protected $template = 'ignicms::admin.formElements.defaultForm';
    /**
     * @var string
     */
    protected $enctype;
    /**
     * @var Model
     */
    protected $model;
    /**
     * @var string
     */
    protected $actionVerb;
    /**
     * @var array
     */
    protected $translatable = [];

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function make($config)
    {
        foreach ($config as $key => $item) {
            if (property_exists($this, $key)) {
                // Todo set this from method
                $this->$key = $item;
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function renderFields()
    {
        $html = '';

        foreach ($this->fields as $field) {
            $html .= $field->toHtml();
        }

        return $html;
    }

    /**
     * @return mixed
     */
    public function toHtml()
    {
        return view($this->getTemplate(), ['form' => $this]);
    }

    protected function beforeToHtml()
    {
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->beforeToHtml();
        $html = $this->toHtml();
        $this->afterHtml($html);

        return $html;
    }

    /**
     * @param Field $field
     * @param       $name
     *
     * @throws \Exception
     */
    public function addField(Field $field, $name)
    {
        if (isset($name)) {
            $this->fields[$name] = $field;
        } else {
            throw new \Exception();
        }
    }

    /**
     * @param $name
     */
    public function removeField($name)
    {
        if (isset($this->fields[$name])) {
            unset($this->fields[$name]);
        }
    }

    /**
     * Gets the value of action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets the value of action.
     *
     * @param string $action the action
     *
     * @return self
     */
    protected function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Gets the value of method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the value of method.
     *
     * @param string $model the Model
     *
     * @return self
     */
    protected function setMethod($method)
    {
        // TODO validation
        $this->method = $method;

        return $this;
    }

    /**
     * Gets the value of role.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Sets the value of role.
     *
     * @param string $role the role
     *
     * @return self
     */
    protected function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Gets the value of attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets the value of attributes.
     *
     * @param array $attributes the attributes
     *
     * @return self
     */
    protected function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Gets the value of template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Sets the value of template.
     *
     * @param string $template the template
     *
     * @return self
     */
    protected function setTemplate($template)
    {
        if (\View::exists($template)) {
            $this->template = $template;
        } else {
            throw new \Exception('This template doesn\'t exist');
        }

        return $this;
    }

    /**
     * Gets the value of enctype.
     *
     * @return string
     */
    public function getEnctype()
    {
        return $this->enctype;
    }

    /**
     * Sets the value of enctype.
     *
     * @param string $enctype the enctype
     *
     * @return self
     */
    protected function setEnctype($enctype)
    {
        $this->enctype = $enctype;

        return $this;
    }

    /**
     * Gets the value of fields.
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Sets the value of fields.
     *
     * @param array $fields the fields
     *
     * @return self
     */
    protected function setFields(array $fields)
    {
        $this->fields = $fields;

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
     * Sets the value of model.
     *
     * @param Model $model the model
     *
     * @return self
     */
    protected function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

     /**
     * Gets the value of translatable.
     *
     * @return array
     */
    public function getTranslatable()
    {
        return $this->translatable;
    }

    /**
     * Sets the value of translatable.
     *
     * @param array $translatable the translatable
     *
     * @return self
     */
    protected function setTranslatable(array $translatable)
    {
        $this->translatable = $translatable;

        return $this;
    }
}
