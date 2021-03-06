<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->post('/register', 'UserController@register');
$router->post('/login', 'UserController@login');
$router->get('/photos', 'PhotoController@showAll');
$router->get('/photos/{id}', 'PhotoController@getDetails');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/example', 'ExampleController@tester');
    $router->post('/photos', 'PhotoController@postPhoto');
    $router->put('/photos/{id}', 'PhotoController@updateDetails');
    $router->delete('/photos/{id}', 'PhotoController@Delete');
    $router->post('/photos/{id}/like', 'LikedPhotoController@LikedPhoto');
    $router->post('/photos/{id}/unlike', 'LikedPhotoController@UnlikedPhoto');
});
