<?php

namespace Despark\Cms\Fields;

class Hidden extends Field
{
	public function __construct($fieldName, array $options, $value = null)
    {
        $options['attributes']['id'] = $fieldName;
        parent::__construct($fieldName, $options, $value);
    }
}
