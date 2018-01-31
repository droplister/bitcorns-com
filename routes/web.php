<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [
    'as' => 'home',
    'uses' => 'PagesController@home',
]);

Route::get('/admin', [
    'as' => 'admin',
    'uses' => 'PagesController@admin',
]);

Route::get('/almanac', [
    'as' => 'almanac',
    'uses' => 'PagesController@almanac',
]);

Route::get('/faq', [
    'as' => 'faq',
    'uses' => 'PagesController@faq',
]);

Route::get('/ico', [
    'as' => 'ico',
    'uses' => 'PagesController@ico',
]);

Route::get('/map', [
    'as' => 'map',
    'uses' => 'PagesController@map',
]);

Route::get('/tokens', [
    'as' => 'tokens.index',
    'uses' => 'TokensController@index',
]);

Route::get('/tokens/create', [
    'as' => 'tokens.create',
    'uses' => 'TokensController@create',
]);

Route::get('/tokens/{token}', [
    'as' => 'tokens.show',
    'uses' => 'TokensController@show',
]);

Route::get('/tokens/{token}/edit', [
    'as' => 'tokens.edit',
    'uses' => 'TokensController@edit',
]);

Route::post('/tokens', [
    'as' => 'tokens.store',
    'uses' => 'TokensController@store',
]);

Route::put('/tokens/{token}', [
    'as' => 'tokens.update',
    'uses' => 'TokensController@update',
]);

Route::get('/txs', [
    'as' => 'txs.index',
    'uses' => 'TxsController@index',
]);

Route::get('/txs/{tx}', [
    'as' => 'txs.show',
    'uses' => 'TxsController@show',
]);

Route::get('/farms', [
    'as' => 'players.index',
    'uses' => 'PlayersController@index',
]);

Route::get('/farms/{player}', [
    'as' => 'players.show',
    'uses' => 'PlayersController@show',
]);

Route::get('/farms/{player}/edit', [
    'as' => 'players.edit',
    'uses' => 'PlayersController@edit',
]);

Route::get('/admin/images', [
    'as' => 'images.index',
    'uses' => 'ImagesController@index',
]);

Route::get('/farms/{player}/images/edit', [
    'as' => 'images.edit',
    'uses' => 'ImagesController@edit',
]);

Route::post('/farms/{player}/images', [
    'as' => 'images.store',
    'uses' => 'ImagesController@store',
]);

Route::put('/farms/{player}/images', [
    'as' => 'images.update',
    'uses' => 'ImagesController@update',
]);

Route::get('/associations', [
    'as' => 'groups.index',
    'uses' => 'GroupsController@index',
]);

Route::get('/associations/create', [
    'as' => 'groups.create',
    'uses' => 'GroupsController@create',
]);

Route::get('/associations/{group}', [
    'as' => 'groups.show',
    'uses' => 'GroupsController@show',
]);

Route::post('/associations', [
    'as' => 'groups.store',
    'uses' => 'GroupsController@store',
]);

Route::put('/associations/{group}', [
    'as' => 'groups.update',
    'uses' => 'GroupsController@update',
]);

Route::get('/associations/{group}/request/create', [
    'as' => 'groupRequests.create',
    'uses' => 'GroupRequestsController@create',
]);

Route::get('/associations/{group}/request/{groupRequest}/edit', [
    'as' => 'groupRequests.edit',
    'uses' => 'GroupRequestsController@edit',
]);

Route::post('/associations/{group}/request/{groupRequest}', [
    'as' => 'groupRequests.store',
    'uses' => 'GroupRequestsController@store',
]);

Route::put('/associations/{group}/request/{groupRequest}', [
    'as' => 'groupRequests.update',
    'uses' => 'GroupRequestsController@update',
]);

Route::get('/harvests', [
    'as' => 'rewards.index',
    'uses' => 'RewardsController@index',
]);

Route::get('/harvests/{reward}', [
    'as' => 'rewards.show',
    'uses' => 'RewardsController@show',
]);