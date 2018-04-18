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
    return redirect(route('map'));
});

Route::get('/api', function () {
    return view('api');
});

Route::get('/buy', function () {
    return view('buy');
});

Route::get('/submit', function () {
    return view('submit');
});

Route::get('/order', function () {
    return view('order');
});

Route::get('/terms', function () {
    return view('terms');
});

Route::get('/privacy', function () {
    return view('privacy');
});

Route::get('/rules', function () {
    return view('rules');
});

Route::get('/world', function () {
    return redirect(route('map'));
});

Auth::routes();

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

Route::get('/sale', [
    'as' => 'sale',
    'uses' => 'PagesController@sale',
]);

Route::get('/map', [
    'as' => 'map',
    'uses' => 'PagesController@map',
]);

Route::get('/tokens', [
    'as' => 'tokens.index',
    'uses' => 'TokensController@index',
]);

Route::get('/tokens/{token}', [
    'as' => 'tokens.show',
    'uses' => 'TokensController@show',
]);

Route::get('/tokens/{token}/edit', [
    'as' => 'tokens.edit',
    'uses' => 'TokensController@edit',
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

Route::put('/farms/{player}', [
    'as' => 'players.update',
    'uses' => 'PlayersController@update',
]);

Route::get('/farms/{player}/upload', [
    'as' => 'uploads.create',
    'uses' => 'UploadsController@create',
]);

Route::post('/farms/{player}/upload', [
    'as' => 'uploads.store',
    'uses' => 'UploadsController@store',
]);

Route::get('/uploads/moderate', [
    'as' => 'uploads.index',
    'uses' => 'UploadsController@index',
]);

Route::put('/uploads/{upload}', [
    'as' => 'uploads.update',
    'uses' => 'UploadsController@update',
]);

Route::get('/coops', [
    'as' => 'groups.index',
    'uses' => 'GroupsController@index',
]);

Route::get('/coops/create', [
    'as' => 'groups.create',
    'uses' => 'GroupsController@create',
]);

Route::get('/coops/{group}', [
    'as' => 'groups.show',
    'uses' => 'GroupsController@show',
]);

Route::post('/coops', [
    'as' => 'groups.store',
    'uses' => 'GroupsController@store',
]);

Route::get('/coops/{group}/edit', [
    'as' => 'groups.edit',
    'uses' => 'GroupsController@edit',
]);

Route::put('/coops/{group}', [
    'as' => 'groups.update',
    'uses' => 'GroupsController@update',
]);

Route::get('/coops/{group}/memberships', [
    'as' => 'memberships.index',
    'uses' => 'MembershipsController@index',
]);

Route::get('/coops/{group}/memberships/create', [
    'as' => 'memberships.create',
    'uses' => 'MembershipsController@create',
]);

Route::get('/farms/{player}/membership', [
    'as' => 'memberships.edit',
    'uses' => 'MembershipsController@edit',
]);

Route::delete('/farms/{player}/membership', [
    'as' => 'memberships.destroy',
    'uses' => 'MembershipsController@destroy',
]);

Route::post('/coops/{group}/memberships', [
    'as' => 'memberships.store',
    'uses' => 'MembershipsController@store',
]);

Route::put('/memberships/{membership}', [
    'as' => 'memberships.update',
    'uses' => 'MembershipsController@update',
]);

Route::get('/harvests', [
    'as' => 'rewards.index',
    'uses' => 'RewardsController@index',
]);

Route::get('/harvests/{reward}', [
    'as' => 'rewards.show',
    'uses' => 'RewardsController@show',
]);

Route::post('/{token}/webhook', function () {
    $updates = Telegram::getWebhookUpdates();
    return 'ok';
});