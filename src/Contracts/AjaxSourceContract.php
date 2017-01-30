<?php


namespace Despark\Cms\Contracts;


interface AjaxSourceContract
{

    /**
     * @return mixed
     */
    public function getOptionByValue($value);

}