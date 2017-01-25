<?php


namespace Despark\Cms\Fields;


use Despark\Cms\Models\AdminModel;

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
     * @param AdminModel $model
     * @param            $fieldName
     * @param array      $options
     * @param null       $elementName
     */
    public function __construct(
        AdminModel $model,
        $fieldName,
        array $options,
        $elementName = null
    ) {
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
        parent::__construct($model, $fieldName, $options, $elementName);

    }

    /**
     * @return array
     */
    public function getSelectOptions()
    {
        if ($this->isAjax()) {
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


    /**
     *
     */
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