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

Route::get('/', 'UserController@showInitialStatistics')->name('user');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'UserController@showInitialStatistics')->name('user');

Route::get('/me','UserController@profile')->name('me');

Route::get('/me/profile','UserController@edit')->name('me.edit');
Route::put('/me/profile', 'UserController@update')->name('me.update');
Route::post('/me/profile', 'UserController@profilePhotoUpload')->name('me.photo.upload');

Route::get('/me/password/{id}','UserController@editPassword')->name('me.editPassword');
Route::put('/me/password/{id}', 'UserController@updatePassword')->name('me.updatePassword');

