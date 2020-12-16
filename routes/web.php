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

Auth::routes(['register' => false]);

Route::get('/home', 'GeneralController@index')->name('home');

// RS Input Page
Route::get('/rsinput', 'GeneralController@rsinput')->name('rsinput');
//Route::post('/getUserData', 'GeneralController@getUserData')->name('getUserData');
Route::post('/submitInput', 'GeneralController@submitInput')->name('submitInput');

// Manage Users
Route::get('/userList', 'UserController@index')->name('userList');
Route::post('/getUserData', 'UserController@getUserData')->name('getUserData');
Route::post('/getUser', 'UserController@getUser')->name('getUser');
Route::post('/updateUser', 'UserController@updateUser')->name('updateUser');
Route::post('/delUser', 'UserController@delete')->name('delUser');

// Manage Companies
Route::get('/companyList', 'CompanyController@index')->name('companyList');
Route::post('/getCompanyData', 'CompanyController@getCompanyData')->name('getCompanyData');
Route::post('/getCompany', 'CompanyController@getCompany')->name('getCompany');
Route::post('/updateCompany', 'CompanyController@updateCompany')->name('updateCompany');
Route::post('/delCompany', 'CompanyController@delete')->name('delCompany');

// Company Profile
Route::get('/companyProfile', 'CompanyController@companyProfile')->name('companyProfile');

Route::post('/submitInput', 'GeneralController@submitInput')->name('submitInput');

// Project List Page
Route::get('/projectlist', 'GeneralController@projectList')->name('projectlist');
Route::get('/requestFile', 'GeneralController@requestFile')->name('requestFile');
Route::post('/getProjectList', 'GeneralController@getProjectList')->name('getProjectList');

// APIs for list, update, download
Route::get('/getJobList', 'APIController@getJobList')->name('getJobList');
Route::get('/updateJobData', 'APIController@updateJobData')->name('updateJobData');
Route::get('/downloadFile', 'APIController@downloadFile')->name('downloadFile');