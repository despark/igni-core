<?php

namespace Despark\Cms\Fields;

class Text extends Field
{
    public function __construct($fieldName, array $options, $value = null)
    {
        $options['attributes']['placeholder'] = $options['label'];
        $options['attributes']['id'] = $fieldName;
        parent::__construct($fieldName, $options, $value);
    }
}
