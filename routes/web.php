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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/private', 'HomeController@private')->name('private');
Route::get('/users', 'HomeController@users')->name('users');

Route::get('/friends', 'HomeController@friends')->name('friends');

Route::get('messages', 'MessageController@fetchMessages');
Route::post('messages', 'MessageController@sendMessage');
Route::get('/private-messages/{user}', 'MessageController@privateMessages')->name('privateMessages');
Route::post('/private-messages/{user}', 'MessageController@sendPrivateMessage')->name('privateMessages.store');


Route::post('/add-user/{user}', 'MessageController@addUser')->name('user.add');
Route::post('/remove-user/{user}', 'MessageController@removeUser')->name('user.remove');
//Route::post('/block-user/{user}', 'MessageController@BlockUser')->name('user.block');
