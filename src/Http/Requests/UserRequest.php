<?php

namespace Despark\Cms\Http\Requests;

class UserRequest extends AdminFormRequest
{
    /**
     * UserRequest constructor.
     */
    public function __construct()
    {
        $userModelClass = config('auth.providers.users.model');
        $this->model = new $userModelClass();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
