<?php

namespace Despark\Cms\Http\Requests;

use Despark\Cms\Resource\EntityManager;
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{

    protected $model;

    //
    protected function getValidatorInstance()
    {
        // We will build our model here
        $entityManager = app(EntityManager::class);
        $config = $entityManager->getByRoute();
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
