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
Route::get('/users', 'HomeController@showUsers')->name('showUsers')->middleware(['auth','admin']);

Route::patch('/users/{user}/block', 'UserController@block')->name('block')->middleware('admin');
Route::patch('/users/{user}/unblock', 'UserController@unblock')->name('unblock')->middleware('admin');
Route::patch('/users/{user}/promote', 'UserController@promote')->name('promote')->middleware('admin');
Route::patch('/users/{user}/demote', 'UserController@demote')->name('demote')->middleware('admin');

//USER PROFILE
Route::get('/me','UserController@myProfile')->name('me');
//EDIT PROFILE
Route::get('/me/profile','UserController@edit')->name('me.edit')->middleware('auth');
Route::put('/me/profile', 'UserController@update')->name('me.update')->middleware('auth');
//EDIT PASSWORD
Route::get('/me/password','UserController@editPassword')->name('me.editPassword')->middleware('auth');
Route::patch('/me/password', 'UserController@updatePassword')->name('me.updatePassword')->middleware('auth');
//ASSOCIATES
Route::get('/me/associates','UserController@associates')->name('me.associates')->middleware('auth');
//ASSOCIATES-OF
Route::get('/me/associates-of','UserController@associatesOf')->name('me.associatesOf')->middleware('auth');
//USER PROFILES
Route::get('/profiles', 'HomeController@showProfiles')->name('profiles');
Route::get('/users/{id}', 'UserController@usersProfile')->name('usersProfile');

//USERS ACCOUNTS
Route::get('/accounts/{user}', 'AccountController@showAccounts')->name('showAccounts');
Route::get('/accounts/{user}/opened', 'AccountController@showAccountsOpened')->name('account.opened');
Route::get('/accounts/{user}/closed', 'AccountController@showAccountsClosed')->name('account.closed');

//SPECIFIED ACCOUNT
Route::delete('/account/{account}', 'AccountController@destroy')->name('accounts.destroy');
Route::patch('/account/{account}/close','AccountController@close')->name('close');
Route::patch('/account/{account}/reopen','AccountController@reopen')->name('reopen');