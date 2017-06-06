<?php


namespace Despark\Cms\Sources;


use Despark\Cms\Contracts\SourceModel;

/**
 * Class Boolean.
 */
class Boolean implements SourceModel
{

    /**
     * @return mixed
     */
    public function toOptionsArray()
    {
        return [
            0 => trans('ignicms::admin.no'),
            1 => trans('ignicms::admin.yes'),
        ];
    }
}