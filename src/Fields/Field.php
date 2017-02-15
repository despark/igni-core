<?php

namespace Despark\Cms\Fields;

use Despark\Cms\Contracts\FieldContract;
use Despark\Cms\Exceptions\Fields\FieldViewNotFoundException;
use Despark\Cms\Models\AdminModel;
use Symfony\Component\Debug\ExceptionHandler;

/**
 * Class Field.
 */
abstract class Field implements FieldContract
{
    /**
     * @var string
     */
    protected $fieldName;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $viewName;

    /**
     * @var
     */
    protected $fieldType;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var bool
     */
    public $hidden = false;

    /**
     * Field constructor.
     *
     * @param AdminModel $model
     * @param string     $fieldName
     * @param array      $options
     */
    public function __construct($fieldName, array $options, $value = null)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->options = $options;
        $this->hidden = isset($options['hidden']) && $options['hidden'];
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getViewName()
    {
        if (! isset($this->viewName)) {
            // Default view name
            $identifier = $this->getModel()->getIdentifier();
            $fieldName = str_slug($this->fieldName).'--field';
            $field = $this->getFieldIdentifier();

            // First check if there is a rewrite on specific field type
            if (\View::exists('resources.'.$identifier.'.formElements.'.$fieldName)) {
                $this->viewName = 'resources.'.$identifier.'.formElements.'.$fieldName;
            } elseif (\View::exists('resources.'.$identifier.'.formElements.'.$field)) {
                $this->viewName = 'resources.'.$identifier.'.formElements.'.$field;
            } elseif (\View::exists('ignicms::admin.formElements.'.$field)) {
                $this->viewName = 'ignicms::admin.formElements.'.$field;
            } else {
                throw new FieldViewNotFoundException('View not found for field '.$this->fieldName);
            }
        }

        return $this->viewName;
    }

    /**
     * @return string
     *
     * @todo check if we cannot directly use field type
     */
    public function getFieldIdentifier()
    {
        return camel_case(strtolower(snake_case(class_basename(get_class($this)))));
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getAttributes()
    {
        $attributes = isset($this->options['attributes']) ? $this->options['attributes'] : [];
        $a = array_merge_recursive(['class' => ['form-control']], $attributes);

        if (isset($a['class']) && is_array($a['class'])) {
            $a['class'] = implode(' ', $a['class']);
        }

        $a['id'] = array_get($a, 'id') ?? $this->getElementName();

        return $a;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            $this->beforeToHtml();
            $html = $this->toHtml();
            $html = $this->afterToHtml($html);
        } catch (\Exception $exc) {
            $eh = new ExceptionHandler(env('APP_DEBUG'));
            die($eh->sendPhpResponse($exc)->__toString());
        }

        return $html;
    }

    protected function beforeToHtml()
    {
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toHtml()
    {
        return view($this->getViewName(), [
            'field' => $this,
            'fieldName' => $this->getFieldName(),
            'elementName' => $this->getElementName(),
        ])->__toString();
    }

    /**
     * @param $html
     *
     * @return mixed
     */
    protected function afterToHtml($html)
    {
        return $html;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * @param mixed|null $key
     * @param mixed|null $default
     *
     * @return mixed|array
     */
    public function getOptions($key = null, $default = null)
    {
        if ($key) {
            return array_get($this->options, $key, $default);
        }

        return $this->options;
    }

    /**
     * @param array      $options
     * @param mixed|null $key
     */
    public function setOptions($options, $key = null)
    {
        if ($key) {
            $this->options[$key] = $options;
        } else {
            $this->options = $options;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getHelp()
    {
        return $this->getOptions('help');
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return array
     */
    public function __call($name, $arguments)
    {
        $action = substr($name, 0, 3);
        if ($action == 'get') {
            $rawProperty = substr($name, 3);
            $property = camel_case(substr($name, 3));

            if (isset($this->$property)) {
                return $this->$property;
            }
        }

        trigger_error('Call to undefined method '.__CLASS__.'::'.$name.'()', E_USER_ERROR);
    }

    /**
     * @param $name
     *
     * @return array
     */
    public function __get($name)
    {
        return $this->getOptions($name);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->setOptions($value, $name);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->options[$name]);
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        if (isset($this->options[$name])) {
            unset($this->options[$name]);
        }
    }

    /**
     * @return mixed
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * @param mixed $fieldType
     *
     * @return $this
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    public function getElementName()
    {
        return $this->getOptions('elementName', $this->getFieldName());
    }
}
