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
Route::post('/getASCEOptions', 'GeneralController@getASCEOptions')->name('getASCEOptions');
Route::post('/getProjectNumComment', 'GeneralController@getProjectNumComment')->name('getProjectNumComment');

// APIs for list, update, download, db backup
Route::get('/getJobList', 'APIController@getJobList')->name('getJobList');
Route::get('/updateJobData', 'APIController@updateJobData')->name('updateJobData');
Route::get('/downloadFile', 'APIController@downloadFile')->name('downloadFile');
Route::get('/getUserInfo', 'APIController@getUserInfo')->name('getUserInfo');
Route::get('/getCompanyList', 'APIController@getCompanyList')->name('getCompanyList');
Route::get('/cronDBBackup', 'APIController@cronDBBackup')->name('cronDBBackup');

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
Route::get('/backupDB', 'AdminController@backupDB')->name('backupDB');
Route::post('/updateDBSetting', 'AdminController@updateDBSetting')->name('updateDBSetting');
Route::post('/manualDBBackup', 'AdminController@manualDBBackup')->name('manualDBBackup');
Route::post('/delBackup', 'AdminController@delBackup')->name('delBackup');
Route::post('/restoreBackup', 'AdminController@restoreBackup')->name('restoreBackup');

// User configuration
Route::get('/settings', 'UserController@settings')->name('settings');
Route::post('/getUserSetting', 'UserController@getUserSetting')->name('getUserSetting');
Route::post('/updateUserSetting', 'UserController@updateUserSetting')->name('updateUserSetting');

//Custom Equipment
Route::get('/customModule', 'EquipmentController@customModule')->name('customModule');
Route::post('/getCustomModules', 'EquipmentController@getCustomModules')->name('getCustomModules');
Route::post('/submitModule', 'EquipmentController@submitModule')->name('submitModule');
Route::post('/deleteModule', 'EquipmentController@deleteModule')->name('deleteModule');
Route::post('/moduleToggleFavorite', 'EquipmentController@moduleToggleFavorite')->name('moduleToggleFavorite');

Route::get('/customInverter', 'EquipmentController@customInverter')->name('customInverter');
Route::post('/getCustomInverters', 'EquipmentController@getCustomInverters')->name('getCustomInverters');
Route::post('/submitInverter', 'EquipmentController@submitInverter')->name('submitInverter');
Route::post('/deleteInverter', 'EquipmentController@deleteInverter')->name('deleteInverter');
Route::post('/inverterToggleFavorite', 'EquipmentController@inverterToggleFavorite')->name('inverterToggleFavorite');

Route::get('/customRacking', 'EquipmentController@customRacking')->name('customRacking');
Route::post('/getCustomRacking', 'EquipmentController@getCustomRacking')->name('getCustomRacking');
Route::post('/submitRacking', 'EquipmentController@submitRacking')->name('submitRacking');
Route::post('/deleteRacking', 'EquipmentController@deleteRacking')->name('deleteRacking');
Route::post('/rackingToggleFavorite', 'EquipmentController@rackingToggleFavorite')->name('rackingToggleFavorite');

Route::get('/customStanchion', 'EquipmentController@customStanchion')->name('customStanchion');
Route::post('/getCustomStanchion', 'EquipmentController@getCustomStanchion')->name('getCustomStanchion');
Route::post('/submitStanchion', 'EquipmentController@submitStanchion')->name('submitStanchion');
Route::post('/deleteStanchion', 'EquipmentController@deleteStanchion')->name('deleteStanchion');
Route::post('/stanchionToggleFavorite', 'EquipmentController@stanchionToggleFavorite')->name('stanchionToggleFavorite');