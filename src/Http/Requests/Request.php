<?php

namespace Despark\Cms\Http\Requests;

use Despark\Cms\Resource\ResourceManager;
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{

    protected $model;

    //
    protected function getValidatorInstance()
    {
        // We will build our model here
        $resourceManager = app(ResourceManager::class);
        $config = $resourceManager->getByRoute();
        if ($config) {
            $this->model = new $config['model'];
        } else {
            throw new \Exception('Cannot find the resource configuration for route');
        }

        return parent::getValidatorInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
        ];
    }
}
