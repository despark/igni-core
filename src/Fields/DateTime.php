<?php

namespace Despark\Cms\Fields;

class DateTime extends Field
{
    /**
     * @return string
     */
    public function getFieldIdentifier()
    {
        $fieldType = $this->getFieldType();

        return camel_case(strtolower(snake_case($fieldType)));
    }
}
