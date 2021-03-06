<?php

namespace Despark\Cms\Illuminate\View;

use Despark\Cms\Helpers\ExceptionHelper;
use Symfony\Component\Debug\ExceptionHandler;

class View extends \Illuminate\View\View
{
    public function __toString()
    {
        try {
            return parent::__toString(); // TODO: Change the autogenerated stub
        } catch (\Exception $exc) {
            ExceptionHelper::logException($exc);
            $eh = new ExceptionHandler(env('APP_DEBUG'));
            die($eh->sendPhpResponse($exc)->__toString());
        }
    }
}
