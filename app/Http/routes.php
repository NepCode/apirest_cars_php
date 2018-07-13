<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// CORS
/*header('Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );*/

/*if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('/api/register','UserController@register')->middleware('cors');
Route::post('/api/login','UserController@login')->middleware('cors');
Route::resource('/api/cars','CarController');



//pruebas
//Route::get('/api/hello', function () { return 'hello world GRRRRRR'; });

/*Route::post('/api/hello', function () {
    $data = array(
        'status' => 'success',
        'code' => 200,
        'message' => 'hello'
    );
    return response()->json($data,200);

});*/

Route::post('/api/hello',['middleware' => 'cors', function () { return 'hello world GRRRRRR'; }]);