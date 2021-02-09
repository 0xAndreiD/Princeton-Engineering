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
Route::post('/recommendUserNum', 'UserController@recommendUserNum')->name('recommendUserNum');

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
Route::post('/getProjectJson', 'GeneralController@getProjectJson')->name('getProjectJson');
Route::post('/getDataCheck', 'GeneralController@getDataCheck')->name('getDataCheck');
Route::post('/delProject', 'GeneralController@delProject')->name('delProject');
Route::post('/setProjectState', 'GeneralController@setProjectState')->name('setProjectState');
Route::post('/setPlanStatus', 'GeneralController@setPlanStatus')->name('setPlanStatus');

// APIs for list, update, download
Route::get('/getJobList', 'APIController@getJobList')->name('getJobList');
Route::get('/updateJobData', 'APIController@updateJobData')->name('updateJobData');
Route::get('/downloadFile', 'APIController@downloadFile')->name('downloadFile');
Route::get('/getUserInfo', 'APIController@getUserInfo')->name('getUserInfo');
Route::get('/getCompanyList', 'APIController@getCompanyList')->name('getCompanyList');

// PV Modules
Route::post('/getPVModules', 'GeneralController@getPVModules')->name('getPVModules');
// PV Inverters
Route::post('/getPVInverters', 'GeneralController@getPVInverters')->name('getPVInverters');
// Stanchions
Route::post('/getStanchions', 'GeneralController@getStanchions')->name('getStanchions');
// Rail Support
Route::post('/getRailsupport', 'GeneralController@getRailsupport')->name('getRailsupport');

// File Uploads
Route::post('/jobFileUpload', 'GeneralController@jobFileUpload')->name('jobFileUpload');
Route::post('/getFileList', 'GeneralController@getFileList')->name('getFileList');
Route::post('/delDropboxFile', 'GeneralController@delDropboxFile')->name('delDropboxFile');
Route::post('/getDownloadLink', 'GeneralController@getDownloadLink')->name('getDownloadLink');
Route::post('/renameFile', 'GeneralController@renameFile')->name('renameFile');
Route::get('/downloadZip', 'GeneralController@downloadZip')->name('downloadZip');

// Backup JSON Inputs on Admin
Route::get('/backupInput', 'AdminController@backupInput')->name('backupInput');
Route::post('/getProjectFiles', 'AdminController@getProjectFiles')->name('getProjectFiles');
Route::post('/backupJSON', 'AdminController@backupJSON')->name('backupJSON');
Route::post('/restoreJSON', 'AdminController@restoreJSON')->name('restoreJSON');