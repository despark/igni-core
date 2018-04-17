<?php


namespace Despark\Cms\Contracts;


interface RequestContract
{

    public function rules();

    public function authorize();

}