<?php

namespace Despark\Cms\Fields\Contracts;

interface Factory
{
    public function make($field, array $options, $value = null);
}
