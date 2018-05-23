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

Route::get('/arcade/memory-game', [
    'uses' => 'Api\ArcadeController@showMemoryGame',
]);

Route::get('/cards', [
    'uses' => 'Api\CardsController@index',
]);

Route::get('/cards/{card}', [
    'uses' => 'Api\CardsController@show',
]);

Route::get('/tokens', [
    'uses' => 'Api\TokensController@index',
]);

Route::get('/tokens/{token}.json', [
    'uses' => 'Api\TokensController@show',
]);

Route::get('/tokens/{token}/supply', [
    'uses' => 'Api\SupplyController@show',
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

Route::get('/coops', [
    'uses' => 'Api\GroupsController@index',
]);

Route::get('/coops/{group}', [
    'uses' => 'Api\GroupsController@show',
]);

Route::get('/harvests', [
    'uses' => 'Api\RewardsController@index',
]);

Route::get('/harvests/{reward}', [
    'uses' => 'Api\RewardsController@show',
]);