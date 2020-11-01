<?php

    /*
    |--------------------------------------------------------------------------
    | Application Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register all of the routes for an application.
    | It is a breeze. Simply tell Lumen the URIs it should respond to
    | and give it the Closure to call when that URI is requested.
    |
    */
    $router->get('foo', function () {
        return  \Illuminate\Support\Facades\DB::select('SHOW VARIABLES LIKE "%version%"');
    });

    $router->group([],function () use ($router) {
        $router->post('register', 'AuthController@register');
        $router->post('login', 'AuthController@login');
    });

    $router->get('/experiments/', [
        'experimentsAll',
        'uses' => 'ExperimentController@getExperimentList'
    ]);
    $router->get('/sensors/', [
        'sensors',
        'uses' => 'ExperimentController@getSensorList'
    ]);
    $router->get('/recalculated/', [
        'recalculated',
        'uses' => 'ExperimentController@getRecalculatedSensorData'
    ]);
    $router->get('/commutationStation/', [
        'recalculated',
        'uses' => 'ExperimentController@getCommutationStationState'
    ]);


    $router->get('/profile',[
        'profile',
        'uses'=>'UserController@getUserProfile'
    ]);
    $router->post('/change/profile',[
        'changeUserProfile',
        'uses'=>'UserController@changeUserProfile'
    ]);
    $router->post('/change/email',[
        'changeEmail',
        'uses'=>'UserController@changeUserEmail'
    ]);
    $router->post('/change/name',[
        'changeName',
        'uses'=>'UserController@changeUserName'
    ]);
    $router->post('/change/password',[
        '/change/password',
        'uses'=>'UserController@changeUserPassword'
    ]);


    $router->get('/tracking/sensors',[
        'trackingSensors',
        'uses'=>'UserController@getUserTrackingSensorsList'
    ]);
    $router->post('/tracking/sensors/remove',[
        'trackingSensorsRemove',
        'uses'=>'UserController@removeSensorFromTrackingList'
    ]);
    $router->post('/tracking/sensors/add',[
        'trackingSensorsAdd',
        'uses'=>'UserController@addSensorToTrackingList'
    ]);


    $router->get('/export/charts',[
        'exportedCharts',
        'uses'=>'UserController@getUserExportedChartList'
    ]);
    $router->post('/export/charts/add',[
        'exportChart',
        'uses'=>'UserController@addChartToExportsList'
    ]);
    $router->post('/export/charts/remove',[
        'removeChart',
        'uses'=>'UserController@removeChartFromExportsList'
    ]);

    $router->post('/exported/chart',[
        'exportedChart',
        'uses'=>'UserController@getExportedChart'
    ]);
