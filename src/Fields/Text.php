<?php

namespace Despark\Cms\Fields;

use Despark\Cms\Models\AdminModel;

class Text extends Field
{
    public function __construct(
        AdminModel $model,
        $fieldName,
        array $options,
        $elementName = null
    ) {
        $options['attributes']['placeholder'] = $options['label'];
        $options['attributes']['id'] = $elementName;
        parent::__construct($model, $fieldName, $options, $elementName);
    }
}
