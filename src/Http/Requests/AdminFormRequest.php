<?php

namespace Despark\Cms\Http\Requests;

use Despark\Cms\Admin\Traits\AdminValidateTrait;
use Despark\Cms\Contracts\RequestContract;

/**
 * Class ProjectRequest.
 */
class AdminFormRequest extends Request implements RequestContract
{
    use AdminValidateTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->method() === 'PUT') {
            return $this->model->getRulesUpdate();
        }

        return $this->model->getRules();
    }

    public function authorize()
    {
        return true;
    }
}
