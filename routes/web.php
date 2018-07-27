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

$router->get('getTheaterListByZip/{zip:[0-9]{5}}', function ($zip) {
    $zip  = (int)$zip;
    
    $alloCine = new \App\Http\Controllers\AlloCineController();
    return $alloCine->getTheaterListByZip($zip);
});

$router->get('getTheaterListByPosition/{lat:[0-9.]*}/{long:[0-9.]*}', function ($lat, $long) {
    $lat  = (float)$lat;
    $long = (float)$long;
    
    $alloCine = new \App\Http\Controllers\AlloCineController();
    return $alloCine->getTheaterListByPosition($lat, $long);
});

$router->get('getShowtimeListByCinema/{code:[a-zA-Z0-9]{5}}', function ($code) {
    $code = strtoupper($code);
    
    $alloCine = new \App\Http\Controllers\AlloCineController();
    return $alloCine->getShowtimeListByCinema($code);
});

$router->get('getShowtimeListByMovie/{cinema_code:[a-zA-Z0-9]{5}}/{movie_code:[a-zA-Z0-9]*}', function ($cinemaCode, $movieCode) {
    $cinemaCode = strtoupper($cinemaCode);
    $movieCode  = strtoupper($movieCode);
    
    $alloCine = new \App\Http\Controllers\AlloCineController();
    return $alloCine->getShowtimeListByMovie($cinemaCode, $movieCode);
});

$router->get('getMovie/{code:[a-zA-Z0-9]*}', function ($code) {
    $code = strtoupper($code);
    
    $alloCine = new \App\Http\Controllers\AlloCineController();
    return $alloCine->getMovie($code);
});
