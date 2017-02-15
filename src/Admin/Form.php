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
     * @param array $fields
     *
     * @return string
     */
    public function make($field)
    {
        $this->setFields($field);

        return $this;
    }

    /**
     * @return mixed
     */
    public function toHtml()
    {
        return view($this->getTemplate(), ['form' => $this]);
    }

    protected function beforeToHtml() { }

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

    public function addField(Field $field, $name)
    {
        if (isset($name)) 
        {
            $this->fields[$name] = $field;
        } 
        else 
        {
            throw new \Exception();
        }
    }

    public function removeField($name)
    {
        if (isset($this->fields[$name])) 
        {
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
    protected function setMethod($model)
    {
        $method = $model->exists ? 'PUT' : 'POST';

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
        if (View::exists($template)) 
        {
            $this->template = $template;
        } 
        else 
        {
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
}
