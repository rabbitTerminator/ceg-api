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


    $router->get('/experiments/', [
        'experimentsAll',
        'uses' => 'ExperimentController@getExperimentList'
    ]);


    $router->get('/sensors/', [
        'sensors',
        'uses' => 'ExperimentController@getSensorList'
    ]);

    $router->get('/recalculated/',[
        'recalculated',
        'uses' => 'ExperimentController@getRecalculatedSensorData'
    ]);

    $router->get('/commutationStation/',[
        'recalculated',
        'uses' => 'ExperimentController@getCommutationStationState'
    ]);
