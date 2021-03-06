<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;

    use Laravel\Lumen\Routing\Controller as BaseController;

    class Controller extends BaseController
    {
        protected function respondWithToken($token)
        {
            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60
            ], 200);
        }

        public function paginate(Collection $collection, $perPage, $total = null, $page = null, $pageName = 'page')
        {
            $perPage = ($perPage == null)
                ? $collection->count() : $perPage;
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            $collection = collect((new LengthAwarePaginator(
                $collection->forPage($page, $perPage),
                $total ?: $collection->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            ))->items());

            foreach ($collection as $key => $value) {
                if (is_object($value)) {
                    $vars = get_object_vars($value);
                    foreach ($vars as $var_key => $var_value) {
                        $vars[$var_key] = utf8_encode($var_value);
                    }

                    $collection[$key] = json_decode(json_encode($vars), false);
                }
            }

            return jsonResponseCollection($collection);
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
    }
