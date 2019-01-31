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

Route::get('/', 'IndexController@index');
Route::get('/game', 'IndexController@game');
Route::get('/check', 'IndexController@check');
Route::post('/action', 'IndexController@action');

Route::post('/api/getGame', 'IndexController@getGame');
Route::post('/api/getdrops', 'IndexController@getdrops');
Route::post('/api/getonline', 'IndexController@getonline');
Route::post('/api/playbot', 'IndexController@bot_game');
Route::get('/sukaoplati190', 'IndexController@sukaoplati190');
Route::get('/yauspel', 'IndexController@yauspel');
Route::get('/login', "IndexController@vklogin");

Route::get("/test333333333333", "IndexController@test");


Route::get('/hash', 'IndexController@hash_generate');

/* adminka */
Route::group(['middleware' => 'auth', 'middleware' => 'access:admin'], function () {
	Route::get('/admin', ['as' => 'admin', 'uses' => 'AdminController@index']);
	/* Players */
	Route::get('/admin/users', ['as' => 'users', 'uses' => 'AdminController@users']);
	Route::post('/admin/user/save', ['as' => 'user.save', 'uses' => 'AdminController@user_save']);
	Route::get('/admin/user/{id}/edit', ['as' => 'user.edit', 'uses' => 'AdminController@edit_user']);
	/* Withdraw */
	Route::get('/admin/withdraw', ['as' => 'withdraw', 'uses' => 'AdminController@withdraw']);
	Route::post('/admin/withdraw/save', ['as' => 'withdraw.save', 'uses' => 'AdminController@withdraw_save']);
	Route::get('/admin/withdraw/{id}/edit', ['as' => 'withdraw.edit', 'uses' => 'AdminController@edit_withdraw']);
	/*Payments*/
	Route::get('/admin/payments', 'AdminController@payments');
	/*Promo*/
	Route::get('/admin/promocodes', 'AdminController@promocodes');
	/*Settings*/
	Route::get('/admin/settings', 'AdminController@settings');
	Route::post('/admin/settings/save', 'AdminController@settings_save');
	Route::post('/admin/createpromo', 'AdminController@createpromo');
	/*Bots*/
	Route::get('/admin/bots', 'AdminController@bots');
	Route::get('/admin/bot/add', 'AdminController@botadd');
	Route::get('/admin/bot/{id}/delete', 'AdminController@bot_delete');
	Route::get('/admin/user/{id}/delete', 'AdminController@user_delete');
	Route::get('/admin/promo/{id}/delete', 'AdminController@promo_delete');
});


/* adminka */

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', 'IndexController@logout');
});
