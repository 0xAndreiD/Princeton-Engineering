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

Route::get('/home', 'GeneralController@index')->name('home');

// RS Input Page
Route::get('/rsinput', 'GeneralController@rsinput')->name('rsinput');
Route::post('/getUserData', 'GeneralController@getUserData')->name('getUserData');
Route::post('/submitInput', 'GeneralController@submitInput')->name('submitInput');

// Project List Page
Route::get('/projectlist', 'GeneralController@projectList')->name('projectlist');
Route::get('/requestFile', 'GeneralController@requestFile')->name('requestFile');

// APIs for list, update, download
Route::get('/getJobList', 'APIController@getJobList')->name('getJobList');
Route::get('/updateJobData', 'APIController@updateJobData')->name('updateJobData');
Route::get('/downloadFile', 'APIController@downloadFile')->name('downloadFile');