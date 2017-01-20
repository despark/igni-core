<?php

namespace Despark\Cms\Http\Requests;

use App\Models\User;
use Despark\Cms\Http\Requests\AdminFormRequest;

class UserUpdateRequest extends UserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = $this->model->getRulesUpdate();
        $rules['email'] = str_replace('{id}', $this->route()->getParameter('user'), $rules['email']);

        return $rules;
    }
}
