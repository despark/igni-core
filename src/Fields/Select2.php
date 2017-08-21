<?php

namespace Despark\Cms\Fields;

use Despark\Cms\Contracts\AjaxSourceContract;

/**
 * Class Select2.
 */
class Select2 extends Select
{
    /**
     * @var bool
     */
    protected $ajax = false;

    /**
     * Select2 constructor.
     *
     * @param string $fieldName
     * @param array  $options
     * @param null   $value
     */
    public function __construct($fieldName, array $options, $value = null)
    {
        // we need to check if we have ajax enabled select2
        if (isset($options['ajaxRoute'])) {
            $this->ajax = true;
            if (! isset($options['attributes'])) {
                $options['attributes'] = [];
            }
            if (! isset($options['attributes']['class'])) {
                $options['attributes']['class'] = [];
            }
            $options['attributes']['class'][] = 'ajax-enabled';
        }
        // Add unique id
        if (! isset($options['attributes']['id'])) {
            $options['attributes']['id'] = uniqid($fieldName);
        }
        if (! isset($options['attributes']['placeholder'])) {
            $options['attributes']['placeholder'] = 'Select '.$options['label'];
        }
        parent::__construct($fieldName, $options, $value);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function getSelectOptions()
    {
        if ($this->isAjax()) {
            // If we have value we need to try and find the ajaxRoute controller.
            // Make sure it implements our interface and get the selected values.
            if (! is_null($this->getValue())) {
                // We need to populate that value
                $route = \Route::getRoutes()->getByName($this->ajaxRoute);
                if ($route) {
                    $action = $route->getActionName();
                    if (is_string($action)) {
                        // Get controller class
                        $controllerClass = explode('@', $action)[0];
                        $instance = app($controllerClass);
                        if ($instance instanceof AjaxSourceContract) {
                            return $instance->getOptionByValue($this->getValue());
                        } else {
                            throw new \Exception('Ajax source route ('.$controllerClass.') must implements '.AjaxSourceContract::class);
                        }
                    } else {
                        throw new \Exception('Ajax source route cannot be closure');
                    }
                }
            }

            return [];
        }

        return $this->getSourceModel()->toOptionsArray();
    }

    /**
     * @return string
     */
    public function getFieldIdentifier()
    {
        return 'select2';
    }

    public function getJSConfig()
    {
        $defaultConfig = [
            'tags' => false,
            'placeholder' => $this->getOptions('attributes')['placeholder'],
        ];
        if ($this->isAjax()) {
            $defaultConfig['minimumInputLength'] = 2;
            $defaultConfig['ajax'] = [
                'url' => route($this->getOptions('ajaxRoute')),
                'dataType' => 'json',
            ];
        }

        $config = array_merge_recursive($defaultConfig, $this->getOptions('config', []));

        return json_encode($config);
    }

    /**
     * @return bool
     */
    public function isAjax()
    {
        return $this->ajax;
    }
}
