<?php

    namespace App\Http\Controllers;


    use App\Http\Requests\CommutationStateRequest;
    use App\Http\Requests\ExperimentListRequest;
    use App\Http\Requests\RecalculatedSensorDataRequest;
    use App\Http\Requests\SensorListRequest;
    use Illuminate\Support\Facades\DB;

    class ExperimentController extends Controller
    {



        const EXPERIMENT_PREFIX = 'mereni_';
        const RECALCULATED_PREFIX = 'prepocitane_';

        public function getSensorList(SensorListRequest $request)
        {

            $input = DB::prepareBindings(
                ['experimentName' => $request->input('experimentName')]
            )['experimentName'];

            $collection = collect(
                DB::select("SELECT * FROM  " .$this::EXPERIMENT_PREFIX. $input . ".cidla ")
            );


            return (
            parent::paginate(
                $collection,
                $request->input('perPage'),
                $request->input('total'),
                $request->input('page')
            )
            );
        }


        public function getExperimentList(ExperimentListRequest $request)
        {
            $collection = getDatabaseListLike(self::EXPERIMENT_PREFIX);
            return (
            parent::paginate(
                $collection,
                $request->input('perPage'),
                $request->input('total'),
                $request->input('page')
            )
            );

        }

        public function getRecalculatedSensorData(RecalculatedSensorDataRequest $request)
        {
            //todo errors if sensor or experiment not exist

            $collection = getSensorById($request->input('sensorId'), $request->input('experimentName'), true)
                ->sort(function ($a, $b)
                {
                return strtotime($a->UTCcas) < strtotime($b->UTCcas);
                }
            );

            return (
            parent::paginate(
                $collection,
                $request->input('perPage'),
                $request->input('total'),
                $request->input('page')
            )
            );
        }

        public function getCommutationStationState(CommutationStateRequest $request)
        {

            $input = DB::prepareBindings(
                [
                    'tableName' => env('EXPERIMENT_PREFIX') . $request->input('experimentName') . '.datazustreden',
                    'comStation' => "'" . $request->input('commutationStation') . "'"
                ]
            );
            $tableName = $input['tableName'];
            $comStation = $input['comStation'];


            $collection = collect(DB::select("SELECT * FROM  $tableName WHERE ustredna = $comStation ORDER BY UTCcas DESC LIMIT 1 "));
            return jsonResponseCollection($collection);
        }

     function  test (int $a ,string $v){

     }
    }
