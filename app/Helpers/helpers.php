<?php
    /**
     * Created by PhpStorm.
     * User: valentindaitkhe
     * Date: 19/11/2019
     * Time: 15:51
     */


    use Illuminate\Support\Facades\DB;

    if (!function_exists('getDatabaseListLike')) {

        function getDatabaseListLike(string $pattern)
        {
            $pattern = (isset($pattern) && !empty($pattern)) ? " '" . $pattern . "%'" : '';

            $pattern = DB::prepareBindings([
                    'pattern' => $pattern]
            );

            $items = DB::select('SHOW DATABASES LIKE ' . $pattern['pattern']);

            $array = [];
            foreach ($items as $item) {
                $item = json_decode(json_encode($item), true);
                array_push($array, array_values($item)[0]);
            }
            return collect($array);
        }
    }

    if (!function_exists('checkExperiment')) {

        function checkExperiment($experimentName)
        {
            $result = getDatabaseListLike(env('EXPERIMENT_PREFIX'))// get all experiments
            ->search(env('EXPERIMENT_PREFIX') . $experimentName); // check experiment name
            return is_numeric($result) ? true : false;
        }
    }
    if (!function_exists('checkSensorsExperimentPair')) {
        function checkSensorsExperimentPair($sensorId, $experimentName, $recalculatedFlag = false)
        {
            if (!checkExperiment($experimentName)) {
                return 0;
            }
            $pattern = DB::prepareBindings([
                    'sensorId' => $sensorId,
                    'experimentName' => $experimentName
                ]
            );
            $sensorId = $pattern['sensorId'];
            $experimentName = $pattern['experimentName'];
            if (!$recalculatedFlag) {
                $collection = collect(DB::select('SELECT * FROM ' . env('EXPERIMENT_PREFIX') . $experimentName . '.cidla WHERE cislo=' . $sensorId));
                return !$collection->isEmpty() ? $collection : null;
            } else {

                $sensorId = str_replace('.', '_', $sensorId);
                $table_name = env('RECALCULATED_PREFIX') . env('EXPERIMENT_PREFIX') . $experimentName . '.' . env('RECALCULATED_PREFIX');

                $collection = collect(DB::select('select  UTCcas, hodnota from ' . $table_name . $sensorId .' order by UTCcas DESC limit 1'));

                if ($collection->isEmpty()) {
                    $collection = collect(DB::select('select UTCcas, hodnota from ' . $table_name . '0' . $sensorId.' order by UTCcas DESC  limit 1'));
                }
                return !$collection->isEmpty() ? $collection : null;

            }

        }
    }
    if (!function_exists('getSensorById')) {
        function getSensorById($sensorId, $experimentName, $flag)
        {
            $pom = checkSensorsExperimentPair($sensorId, $experimentName, $flag);
            return is_a($pom, \Illuminate\Support\Collection::class) ? $pom : collect();
        }
    }

    if(!function_exists('getSensorsDataRange')){
        function getSensorsDataRange($sensorId,$experimentName,$startDate,$endDate){
            $sensorId = str_replace('.', '_', $sensorId);
            $table_name = env('RECALCULATED_PREFIX') . env('EXPERIMENT_PREFIX') . $experimentName . '.' . env('RECALCULATED_PREFIX');
            $collection = null;
           if($endDate != null){
               $collection = DB::table( $table_name . $sensorId )
                                ->whereDate('UTCcas','>=',\Illuminate\Support\Facades\Date::make($startDate))
                                ->whereDate('UTCcas','>=',\Illuminate\Support\Facades\Date::make($endDate))
                                ->get();
           }else{
               $collection = DB::table( $table_name . $sensorId )
                                ->whereDate('UTCcas','>=',\Illuminate\Support\Facades\Date::make($startDate))
                                ->get();
           }

           return $collection;

        }
    }
    if (!function_exists('jsonResponseCollection')) {
        function jsonResponseCollection(\Illuminate\Support\Collection $collection)
        {

            return $collection->isEmpty() ?
                fallback() :
                response()
                    ->json(
                        $collection,
                        200,
                        [
                            'Content-type' => 'application/json',
                            'Charset' => 'utf-8'
                        ],
                        JSON_FORCE_OBJECT
                    );
        }
    }
    if (!function_exists('fallback')) {
        function fallback()
        {
            return response()->json(['message' => 'Not Found.'],
                404,
                [
                    'Content-type' => 'application/json',
                    'Charset' => 'utf-8'
                ]
            );
        }
    }
