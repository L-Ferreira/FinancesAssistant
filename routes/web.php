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
Route::get('/users', 'UserController@showUsers')->name('showUsers');
Route::patch('/users/{user}/block', 'AdminController@block');
Route::patch('/users/{user}/unblock', 'AdminController@unblock');
Route::patch('/users/{user}/promote', 'AdminController@promote');
Route::patch('/users/{user}/demote', 'AdminController@demote');