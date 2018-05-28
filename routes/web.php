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
Route::get('/users', 'HomeController@showUsers')->name('showUsers');

Route::patch('/users/{user}/block', 'AdminController@block');
Route::patch('/users/{user}/unblock', 'AdminController@unblock');
Route::patch('/users/{user}/promote', 'AdminController@promote');
Route::patch('/users/{user}/demote', 'AdminController@demote');

//USER PROFILE
Route::get('/me','UserController@myProfile')->name('me');
//EDIT PROFILE
Route::get('/me/profile','UserController@edit')->name('me.edit')->middleware('auth');
Route::put('/me/profile', 'UserController@update')->name('me.update')->middleware('auth');
//EDIT PASSWORD
Route::get('/me/password','UserController@editPassword')->name('me.editPassword')->middleware('auth');
Route::patch('/me/password', 'UserController@updatePassword')->name('me.updatePassword')->middleware('auth');

//USER PROFILES
Route::get('/profiles', 'HomeController@showProfiles')->name('profiles');
Route::get('/users/{id}', 'UserController@usersProfile')->name('usersProfile');


//USERS ACCOUNTS
Route::get('/accounts/{user}', 'AccountController@showAccounts')->name('showAccounts');
Route::get('/accounts/{user}/opened', 'AccountController@showAccountsOpened')->name('account.opened');
Route::get('/accounts/{user}/closed', 'AccountController@showAccountsClosed')->name('account.closed');