<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/map', [
    'uses' => 'Api\GoogleMapController@index',
]);

Route::get('/map/{group}', [
    'uses' => 'Api\GoogleMapController@show',
]);

Route::get('/tokens', [
    'uses' => 'Api\TokensController@index',
]);

Route::get('/tokens/{token}.json', [
    'uses' => 'Api\TokensController@show',
]);

Route::get('/txs', [
    'uses' => 'Api\TxsController@index',
]);

Route::get('/txs/{tx}', [
    'uses' => 'Api\TxsController@show',
]);

Route::get('/farms', [
    'uses' => 'Api\PlayersController@index',
]);

Route::get('/farms/{player}', [
    'uses' => 'Api\PlayersController@show',
]);

Route::get('/harvests', [
    'uses' => 'Api\RewardsController@index',
]);

Route::get('/harvests/{reward}', [
    'uses' => 'Api\RewardsController@show',
]);