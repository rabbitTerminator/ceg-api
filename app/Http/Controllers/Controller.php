<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\DB;

    use Laravel\Lumen\Routing\Controller as BaseController;

    class Controller extends BaseController
    {


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


    }
