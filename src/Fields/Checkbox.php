<?php

namespace Despark\Cms\Fields;

class Checkbox extends Field
{
    public function getAttributes()
    {
        return isset($this->options['attributes']) ? $this->options['attributes'] : [];
    }
}
