<?php

namespace Despark\Cms\Fields;

class Checkbox extends Field
{
    public function getAttributes()
    {
        $attributes = array_get($this->options, 'attributes', []);
        $attributes['id'] = array_get($attributes, 'id') ?? $this->getElementName();

        return $attributes;
    }
}
