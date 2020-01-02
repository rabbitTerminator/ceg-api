<?php
    /**
     * Created by PhpStorm.
     * User: valentindaitkhe
     * Date: 18/11/2019
     * Time: 21:28
     */

    namespace App\Http\Requests\RequestRules;


    use Illuminate\Contracts\Validation\ImplicitRule;
    use Illuminate\Database\DatabaseManager;


    class ExperimentNameRule implements ImplicitRule
    {
        protected  $experimentName;

        /**
         * Determine if the validation rule passes.
         *
         * @param  string $attribute
         * @param  mixed $value
         * @return bool
         */
        public function passes($attribute, $value)
        {
            $this->experimentName = $value;
            return checkExperiment($value);
        }

        /**
         * Get the validation error message.
         *
         * @return string|array
         */
        public function message()
        {
            $name = $this->experimentName;
            return "Experiment: '$name' doesn't exist";
        }

        public function getExperimentName(){
            return $this->experimentName;
        }
    }
