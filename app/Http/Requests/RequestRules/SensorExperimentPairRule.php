<?php
    /**
     * Created by PhpStorm.
     * User: valentindaitkhe
     * Date: 25/11/2019
     * Time: 15:19
     */

    namespace App\Http\Requests\RequestRules;


    use App\Http\Requests\RecalculatedSensorDataRequest;
    use Illuminate\Contracts\Validation\ImplicitRule;

    class SensorExperimentPairRule extends ExperimentNameRule implements  ImplicitRule
    {
          protected $request;

        public function __construct(RecalculatedSensorDataRequest $request)
        {
           $this->request = $request;
        }

        /**
         * Determine if the validation rule passes.
         *
         * @param  string $attribute
         * @param  mixed $value
         * @return bool
         */

        public function passes($attribute, $value)
        {

           dd(checkSensorsExperimentPair($this->request->input('sensorId'),$this->request->input('experimentName')));
           if (is_array(checkSensorsExperimentPair($this->sensorId,$this->experimentName))){
               return true;
           }else{
               return false;
           }
        }

        /**
         * Get the validation error message.
         *
         * @return string|array
         */
        public function message()
        {
            return ' Sensor with id (cislo) :' .$this->sensorId .'  not found';
        }
    }
