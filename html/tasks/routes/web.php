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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// list of user's tasks
$router->get('/task/user/{id}', 'TaskController@getListForUser');

// get one task by id
$router->get('/task/{id}', 'TaskController@get');

// create a task
$router->put('/task', 'TaskController@store');

// update a task
$router->post('/task/{id}', 'TaskController@update');

// delete a task
$router->delete('/task/{id}', 'TaskController@delete');
