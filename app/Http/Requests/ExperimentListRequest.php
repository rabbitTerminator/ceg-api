<?php

namespace App\Http\Requests;

use Pearl\RequestValidate\RequestAbstract;

class ExperimentListRequest extends RequestAbstract
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'perPage'=>'numeric|nullable',
            'total' => 'numeric|nullable',
            'page' =>  'numeric|nullable',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'perPage.numeric' =>'perPage: parameter must be a num value',
            'total.numeric' =>'total: parameter must be a num value',
            'page.numeric' =>'page: parameter must be a num value',
        ];
    }
}
