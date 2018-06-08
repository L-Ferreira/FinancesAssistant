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

//SHOW WELCOME PAGE STATISTICS
Route::get('/', 'UserController@showInitialStatistics')->name('user');


//ADMIN USERS INDEX
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/users', 'HomeController@showUsers')->name('showUsers')->middleware(['auth','admin']);
// BLOCK / UNBLOCK USER
Route::patch('/users/{user}/block', 'UserController@block')->name('block')->middleware('admin');
Route::patch('/users/{user}/unblock', 'UserController@unblock')->name('unblock')->middleware('admin');
// PROMOTE / DEMOTE USER
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
Route::get('/me/associate-of','UserController@associateOf')->name('me.associateOf')->middleware('auth');
//USERS PROFILES
Route::get('/profiles', 'HomeController@showProfiles')->name('profiles');
Route::get('/users/{id}', 'UserController@usersProfile')->name('usersProfile');



//USERS ACCOUNTS
Route::get('/accounts/{user}', 'AccountController@showAccounts')->name('showAccounts');
Route::get('/accounts/{user}/opened', 'AccountController@showAccountsOpened')->name('account.opened');
Route::get('/accounts/{user}/closed', 'AccountController@showAccountsClosed')->name('account.closed');
//CREATE ACCOUNT
Route::get('/account','AccountController@create')->name('account.create');
Route::post('/account','AccountController@store')->name('account.store');
//EDIT ACCOUNT
Route::get('/account/{account}','AccountController@edit')->name('account.edit');
Route::put('/account/{account}', 'AccountController@update')->name('account.update');
//DELETE SPECIFIED ACCOUNT
Route::delete('/account/{account}', 'AccountController@destroy')->name('accounts.destroy');
//CLOSE SPECIFIED ACCOUNT
Route::patch('/account/{account}/close','AccountController@close')->name('close');
//REOPEN SPECIFIED ACCOUNT
Route::patch('/account/{account}/reopen','AccountController@reopen')->name('reopen');



//ACCOUNTS MOVEMENTS
Route::get('/movements/{account}','MovementController@accountMovements')->name('account.movement');
//CREATE MOVEMENT
Route::get('/movements/{account}/create','MovementController@create')->name('movement.create');
Route::post('/movements/{account}/create','MovementController@store')->name('movement.store');
//EDIT MOVEMENT
Route::get('/movement/{movement}','MovementController@edit')->name('movement.edit');
Route::put('/movement/{movement}','MovementController@update')->name('movement.update');
//DELETE MOVEMENT
Route::delete('/movement/{movement}', 'MovementController@destroy')->name('movement.destroy');
//ADD DOCUMENT
Route::get('/documents/{movement}','DocumentController@document')->name('document.document');
Route::post('/documents/{movement}','DocumentController@associateDocument')->name('document.associateDocument');
//DELETE DOCUMENT OR DISASSOCIATE
Route::get('/document/{document}', 'DocumentController@viewDocument')->name('document.viewDocument');