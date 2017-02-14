<?php

namespace Despark\Cms\Fields\Contracts;

use Despark\Cms\Models\AdminModel;

interface Factory
{
    public function make(AdminModel $model, $field, array $options);
}
