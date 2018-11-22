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

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('threads', 'ThreadsController@index');
Route::get('threads/create', 'ThreadsController@create');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show'); //this one is to show the thread
Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy'); //this one is to delete the thread
Route::post('threads', 'ThreadsController@store');
Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::delete('/replies/{reply}', 'RepliesController@destroy'); //delete the reply

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::get('threads/{channel}', 'ThreadsController@index');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('threads/{channel}/{thread}/replies', 'RepliesController@index'); //route for the vue, we need to create the index function inside repliescontroller
Route::patch('/replies/{reply}', 'RepliesController@update');


Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy'); //this is the endpoint for the sub.notifications
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');

Route::get('api/users', 'Api\UsersController@index');