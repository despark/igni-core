<?php

namespace resources;

use Despark\LaravelDbLocalization\Contracts\Translatable;
use Despark\LaravelDbLocalization\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Model;

class TestModelWithTranslations extends Model implements Translatable
{
    use HasTranslation;

    /**
     * @var array
     */
    public $translatable = [];
}
