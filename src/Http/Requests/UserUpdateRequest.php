<?php

namespace Despark\Cms\Http\Requests;

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
        $rules['email'] = str_replace('{id}', $this->route()->parameter('user'), $rules['email']);

        return $rules;
    }
}
