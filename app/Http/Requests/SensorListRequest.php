<?php

namespace App\Http\Requests;

use App\Http\Requests\RequestRules\ExperimentNameRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Pearl\RequestValidate\RequestAbstract;

class SensorListRequest extends RequestAbstract
{


    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        return [
            'experimentName' =>['required', new ExperimentNameRule ],
            'perPage'=>'numeric|nullable',
            'total' => 'numeric|nullable',
            'page' =>  'numeric|nullable',

        ];
    }


}
