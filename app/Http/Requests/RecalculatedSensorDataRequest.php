<?php

namespace App\Http\Requests;

use App\Http\Requests\RequestRules\ExperimentNameRule;
use App\Http\Requests\RequestRules\SensorExperimentPairRule;
use Pearl\RequestValidate\RequestAbstract;

class RecalculatedSensorDataRequest extends RequestAbstract
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
            'sensorId' => ['required','numeric'],
            'experimentName'=> ['required','string',new ExperimentNameRule],
            'startDate'=>['required','date'],
            'endDate' =>['nullable','date']
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

        ];
    }
}
