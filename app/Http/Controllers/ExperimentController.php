<?php

    namespace App\Http\Controllers;


    use App\Http\Requests\CommutationStateRequest;
    use App\Http\Requests\ExperimentListRequest;
    use App\Http\Requests\RecalculatedSensorDataRequest;
    use App\Http\Requests\SensorListRequest;
    use App\User;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\QueryException;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Response;

    class ExperimentController extends Controller
    {


        const EXPERIMENT_PREFIX = 'mereni_';
        const RECALCULATED_PREFIX = 'prepocitane_';
        const SENSOR_COLUMNS = ['x','y','r','z','alfa','ID', 'cislo', 'popis', 'typ' , 'jednotky' ,'od','do'];

        public function __construct()
        {
            $this->middleware('auth');
        }

        public function clearUnusedData(\Illuminate\Support\Collection $data){

           return $data->map(function ($item){
                $item = get_object_vars($item);
                foreach ($item as $key => $value){
                    if(!in_array($key,self::SENSOR_COLUMNS,true)){
                        unset($item[$key]);
                    }
                }
                return (object)$item;
           });
        }
        public function getSensorList(SensorListRequest $request)
        {
            $input = DB::prepareBindings(
                ['experimentName' => $request->input('experimentName')]
            )['experimentName'];

            $collection = collect(
                DB::select("SELECT * FROM " . $this::EXPERIMENT_PREFIX . $input . ".cidla ")
            );
            $collection = $this->clearUnusedData($collection);
            $collection->map(function ($value) use ($input) {
                try {
                    $sensorsData =  $this->getSenorLastValue($value->cislo, $input);
                    if(isset($sensorsData) && !empty($sensorsData) ){
                        $value->value =$sensorsData["hodnota"];
                        $value->date = $sensorsData["UTCcas"];
                    }else{
                        $value->value = null;
                        $value->date = null;
                    }

                } catch (QueryException $exception) {
                    $value->value = null;
                    $value->date = null;

                }
                return $value;
            });


            foreach ($collection as $key => $value) {
                if (is_object($value)) {
                    $vars = get_object_vars($value);
                    foreach ($vars as $var_key => $var_value) {
                        if (is_string($var_value)) {
                            $en = mb_detect_encoding($var_value);
                            $vars[$var_key] = $value = iconv($en, 'utf-8//TRANSLIT', $var_value);

                        }
                        $collection[$key] = json_decode(json_encode($vars), false);
                    }
                }


            }
            return jsonResponseCollection($collection);
        }

        public function getExperimentList(ExperimentListRequest $request)
        {
            $collection = collect();
            foreach (getDatabaseListLike(self::EXPERIMENT_PREFIX) as $item) {

                $item = str_replace('mereni_', '', $item);
                $realItem = $item;
                $item = ucfirst(str_replace('_', ' ', $item));

                $item = [
                    'id' => uniqid(),
                    'data' => $item,
                    'sensors' => true,
                    'ustredna' => false,
                    'realData' => $realItem
                ];
                $collection->add($item);
            }
            return $collection;
        }

        public function getSenorLastValue($sensorId, $experimentName)
        {
            $collection = getSensorById($sensorId, $experimentName, true);
            $cas = isset($collection->first->UTCcas->UTCcas) ? $collection->first->UTCcas->UTCcas : 'null';
            $hod = isset($collection->first->hodnota->hodnota)? $collection->first->hodnota->hodnota:'null';
            return ([
                'UTCcas' => utf8_encode($cas),
                'hodnota' => utf8_encode($hod)
            ]);
        }

        public function getRecalculatedSensorData(RecalculatedSensorDataRequest $request)
        {
           return getSensorsDataRange(
                             $request->input('sensorId'),
                             $request->input('experimentName'),
                             $request->input('startDate'),
                             $request->input('endtDate')
           )->toJson();

        }


        public function getCommutationStationState(CommutationStateRequest $request)
        {

            $input = DB::prepareBindings(
                [
                    'tableName' => $this::EXPERIMENT_PREFIX . $request->input('experimentName') . '.datazustreden',
                    'comStation' => "'" . $request->input('commutationStation') . "'"
                ]
            );
            $tableName = $input['tableName'];
            $comStation = $input['comStation'];


            $collection = collect(DB::select("SELECT * FROM  $tableName WHERE ustredna = $comStation ORDER BY UTCcas DESC LIMIT 1 "));
            return jsonResponseCollection($collection);
        }




    }
