<?php


namespace Despark\Cms\Contracts;


interface RequestContract
{

    public function validate();

    public function rules();

    public function authorize();

}