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

Route::get('/', 'WelcomeController@index');

Auth::routes(['register' => false]);

Route::get('/home', 'GeneralController@index')->name('home');
Route::get('/statistics', 'GeneralController@statistics')->name('statistics');
Route::post('/getCompanySummary', 'GeneralController@getCompanySummary')->name('getCompanySummary');
Route::post('/getUserMetrics', 'GeneralController@getUserMetrics')->name('getUserMetrics');
Route::post('/getUserSummary', 'GeneralController@getUserSummary')->name('getUserSummary');
Route::post('/getUserProjects', 'GeneralController@getUserProjects')->name('getUserProjects');

// RS Input Page
Route::get('/rsinput', 'GeneralController@rsinput')->name('rsinput');
//Route::post('/getUserData', 'GeneralController@getUserData')->name('getUserData');
Route::post('/submitInput', 'GeneralController@submitInput')->name('submitInput');
Route::post('/submitProjectManager', 'GeneralController@submitProjectManager')->name('submitProjectManager');
Route::post('/submitPermitInput', 'GeneralController@submitPermitInput')->name('submitPermitInput');
Route::post('/submitPDF', 'GeneralController@submitPDF')->name('submitPDF');
Route::get('/reference', 'GeneralController@reference')->name('reference');
Route::post('/getPermitFields', 'GeneralController@getPermitFields')->name('getPermitFields');

// Manage Users
Route::get('/userList', 'UserController@index')->name('userList');
Route::post('/getUserData', 'UserController@getUserData')->name('getUserData');
Route::post('/getUser', 'UserController@getUser')->name('getUser');
Route::post('/updateUser', 'UserController@updateUser')->name('updateUser');
Route::post('/delUser', 'UserController@delete')->name('delUser');
Route::post('/recommendUserNum', 'UserController@recommendUserNum')->name('recommendUserNum');

// Manage Companies
Route::get('/companyList', 'CompanyController@index')->name('companyList');
Route::get('/subclients', 'CompanyController@subclients')->name('subclients');
Route::get('/editCompany', 'CompanyController@editCompany')->name('editCompany');
Route::post('/getCompanyData', 'CompanyController@getCompanyData')->name('getCompanyData');
Route::post('/getSubClients', 'CompanyController@getSubClients')->name('getSubClients');
Route::post('/getSubClient', 'CompanyController@getSubClient')->name('getSubClient');
Route::post('/updateSubClient', 'CompanyController@updateSubClient')->name('updateSubClient');
Route::post('/delSubClient', 'CompanyController@delSubClient')->name('delSubClient');
Route::post('/getCompany', 'CompanyController@getCompany')->name('getCompany');
Route::post('/updateCompany', 'CompanyController@updateCompany')->name('updateCompany');
Route::post('/delCompany', 'CompanyController@delete')->name('delCompany');
Route::post('/getPermitInfo', 'CompanyController@getPermitInfo')->name('getPermitInfo');
Route::post('/updatePermitInfo', 'CompanyController@updatePermitInfo')->name('updatePermitInfo');
// Route::get('/billinginfo', 'CompanyController@billinginfo')->name('billinginfo');
// Route::post('/getCompanyBilling', 'CompanyController@getCompanyBilling')->name('getCompanyBilling');

// Company Info
Route::get('/companyInfo', 'CompanyController@companyInfo')->name('companyInfo');

// Company Bills
Route::get('/bills', 'CompanyController@bills')->name('bills');
Route::post('/getBills', 'CompanyController@getBills')->name('getBills');
Route::get('/invoice', 'CompanyController@getInvoice')->name('invoice');
Route::post('/chargeNow', 'CompanyController@chargeNow')->name('chargeNow');
Route::post('/markAsPaid', 'CompanyController@markAsPaid')->name('markAsPaid');
Route::post('/getBillData', 'CompanyController@getBillData')->name('getBillData');
Route::post('/delBill', 'CompanyController@delBill')->name('delBill');
Route::post('/saveBill', 'CompanyController@saveBill')->name('saveBill');
Route::post('/setBillState', 'CompanyController@setBillState')->name('setBillState');
Route::post('/getBillingInfo', 'CompanyController@getBillingInfo')->name('getBillingInfo');
Route::post('/saveBillingInfo', 'CompanyController@saveBillingInfo')->name('saveBillingInfo');
Route::post('/getPaymentShortInfo', 'CompanyController@getPaymentShortInfo')->name('getPaymentShortInfo');
Route::post('/billNow', 'CompanyController@billNow')->name('billNow');

// Project List Page
Route::get('/projectlist', 'GeneralController@projectList')->name('projectlist');
Route::get('/requestFile', 'GeneralController@requestFile')->name('requestFile');
Route::post('/getProjectList', 'GeneralController@getProjectList')->name('getProjectList');
Route::post('/getProjectJson', 'GeneralController@getProjectJson')->name('getProjectJson');
Route::post('/getProjectPermitJson', 'GeneralController@getProjectPermitJson')->name('getProjectPermitJson');
Route::post('/getDataCheck', 'GeneralController@getDataCheck')->name('getDataCheck');
Route::post('/delProject', 'GeneralController@delProject')->name('delProject');
Route::post('/setProjectState', 'GeneralController@setProjectState')->name('setProjectState');
Route::post('/getProjectState', 'GeneralController@getProjectState')->name('getProjectState');
Route::post('/setPlanStatus', 'GeneralController@setPlanStatus')->name('setPlanStatus');
Route::post('/setESeal', 'GeneralController@setESeal')->name('setESeal');
Route::post('/resetReviewChecks', 'GeneralController@resetReviewChecks')->name('resetReviewChecks');
Route::post('/getASCEOptions', 'GeneralController@getASCEOptions')->name('getASCEOptions');
Route::post('/getProjectNumComment', 'GeneralController@getProjectNumComment')->name('getProjectNumComment');
Route::get('/jobchat', 'GeneralController@jobChat')->name('jobchat');
Route::post('/submitChat', 'GeneralController@submitChat')->name('submitChat');
Route::post('/delChat', 'GeneralController@delChat')->name('delChat');
Route::post('/updateChat', 'GeneralController@updateChat')->name('updateChat');
Route::post('/togglePlanCheck', 'GeneralController@togglePlanCheck')->name('togglePlanCheck');
Route::post('/toggleAsBuilt', 'GeneralController@toggleAsBuilt')->name('toggleAsBuilt');
Route::post('/togglePIL', 'GeneralController@togglePIL')->name('togglePIL');
Route::post('/togglePrintCheck', 'GeneralController@togglePrintCheck')->name('togglePrintCheck');
Route::post('/checkChatList', 'GeneralController@checkChatList')->name('checkChatList');
Route::post('/getPermitList', 'GeneralController@getPermitList')->name('getPermitList');
Route::post('/getPILList', 'GeneralController@getPILList')->name('getPILList');
Route::post('/getCompanyInfo', 'GeneralController@getCompanyInfo')->name('getCompanyInfo');
Route::get('/onreview', 'GeneralController@onReview')->name('onReview');
Route::get('/jobFiles', 'GeneralController@jobFiles')->name('jobFiles');
Route::post('/getMainJobFiles', 'GeneralController@getMainJobFiles')->name('getMainJobFiles');
Route::post('/getPrintFiles', 'GeneralController@getPrintFiles')->name('getPrintFiles');
Route::get('/report/{file}', 'GeneralController@getReport');
Route::get('/in/{jobId}/{file}', 'GeneralController@getINFile');
Route::post('/setReviewer', 'GeneralController@setReviewer')->name('setReviewer');
Route::post('/checkReviewer', 'GeneralController@checkReviewer')->name('checkReviewer');
Route::post('/checkCorrectTown', 'GeneralController@checkCorrectTown')->name('checkCorrectTown');
Route::post('/getJobSubClients', 'GeneralController@getJobSubClients')->name('getJobSubClients');
Route::post('/isSubClientAllowed', 'GeneralController@isSubClientAllowed')->name('isSubClientAllowed');
Route::post('/jobSubClientAllowed', 'GeneralController@jobSubClientAllowed')->name('jobSubClientAllowed');
Route::post('/savePrint', 'GeneralController@savePrint')->name('savePrint');
Route::post('/deletePrint', 'GeneralController@deletePrint')->name('deletePrint');
Route::post('/submitPrint', 'GeneralController@submitPrint')->name('submitPrint');
Route::post('/getActionType', 'GeneralController@getActionType')->name('getActionType');

// APIs for list, update, download, db backup, bill request
Route::get('/getJobList', 'APIController@getJobList')->name('getJobList');
Route::get('/updateJobData', 'APIController@updateJobData')->name('updateJobData');
Route::get('/downloadFile', 'APIController@downloadFile')->name('downloadFile');
Route::get('/getUserInfo', 'APIController@getUserInfo')->name('getUserInfo');
Route::get('/getCompanyList', 'APIController@getCompanyList')->name('getCompanyList');
Route::get('/cronDBBackup', 'APIController@cronDBBackup')->name('cronDBBackup');
Route::get('/cronSendBill', 'APIController@cronSendBill')->name('cronSendBill');
Route::get('/cronStorageDelete', 'APIController@cronStorageDelete')->name('cronStorageDelete');
Route::get('/getCustomEquipment', 'APIController@getCustomEquipment')->name('getCustomEquipment');
Route::get('/getChat', 'APIController@getChat')->name('getChat');
Route::get('/addChat', 'APIController@addChat')->name('addChat');
Route::post('/delChatApi', 'APIController@delChat')->name('delChatApi');
Route::post('/updateChatApi', 'APIController@updateChat')->name('updateChatApi');
Route::get('/getCsrfToken', 'APIController@getCsrfToken')->name('getCsrfToken');
Route::post('/sendEmail', 'APIController@sendEmail')->name('sendEmail');
Route::get('/removeSpecialChars', 'APIController@removeSpecialChars')->name('removeSpecialChars');
Route::post('/createJob', 'APIController@createJob')->name('createJob');
Route::post('/updateElectric', 'APIController@updateElectric')->name('updateElectric');
Route::post('/uploadJobFile', 'APIController@uploadJobFile')->name('uploadJobFile');
Route::post('/getJobStatus', 'APIController@getJobStatus')->name('getJobStatus');
Route::get('/downloadJobFiles', 'APIController@downloadJobFiles')->name('downloadJobFiles');

// PV Modules
Route::post('/getCECPVModules', 'GeneralController@getCECPVModules')->name('getCECPVModules');
Route::post('/resetCEC', 'GeneralController@resetCEC')->name('resetCEC');
Route::post('/getPVModules', 'GeneralController@getPVModules')->name('getPVModules');
// PV Inverters
Route::post('/getPVInverters', 'GeneralController@getPVInverters')->name('getPVInverters');
// Stanchions
Route::post('/getStanchions', 'GeneralController@getStanchions')->name('getStanchions');
// Rail Support
Route::post('/getRailsupport', 'GeneralController@getRailsupport')->name('getRailsupport');

// File Uploads
Route::post('/jobFileUpload', 'GeneralController@jobFileUpload')->name('jobFileUpload');
Route::post('/jobFilePush', 'GeneralController@jobFilePush')->name('jobFilePush');
Route::post('/getFileList', 'GeneralController@getFileList')->name('getFileList');
Route::post('/delDropboxFile', 'GeneralController@delDropboxFile')->name('delDropboxFile');
Route::post('/getDownloadLink', 'GeneralController@getDownloadLink')->name('getDownloadLink');
Route::post('/renameFile', 'GeneralController@renameFile')->name('renameFile');
Route::get('/downloadZip', 'GeneralController@downloadZip')->name('downloadZip');

// Admin-use-only APIs
Route::get('/backupInput', 'AdminController@backupInput')->name('backupInput');
Route::post('/getProjectFiles', 'AdminController@getProjectFiles')->name('getProjectFiles');
Route::post('/backupJSON', 'AdminController@backupJSON')->name('backupJSON');
Route::post('/restoreJSON', 'AdminController@restoreJSON')->name('restoreJSON');
Route::get('/backupDB', 'AdminController@backupDB')->name('backupDB');
Route::post('/updateDBSetting', 'AdminController@updateDBSetting')->name('updateDBSetting');
Route::post('/manualDBBackup', 'AdminController@manualDBBackup')->name('manualDBBackup');
Route::post('/delBackup', 'AdminController@delBackup')->name('delBackup');
Route::post('/restoreBackup', 'AdminController@restoreBackup')->name('restoreBackup');
Route::get('/guardlist', 'AdminController@guardlist')->name('guardlist');
Route::post('/getGuardList', 'AdminController@getGuardList')->name('getGuardList');
Route::post('/toggleBlock', 'AdminController@toggleBlock')->name('toggleBlock');
Route::post('/deleteGuard', 'AdminController@deleteGuard')->name('deleteGuard');

// User configuration
Route::get('/settings', 'UserController@settings')->name('settings');
Route::post('/getUserSetting', 'UserController@getUserSetting')->name('getUserSetting');
Route::post('/updateUserSetting', 'UserController@updateUserSetting')->name('updateUserSetting');
Route::get('/myaccount', 'UserController@myaccount')->name('myaccount');
Route::post('/updateMyAccount', 'UserController@updateMyAccount')->name('updateMyAccount');

Route::get('/permit', 'PermitController@index')->name('permit');
Route::get('/configPermit', 'PermitController@configPermit')->name('configPermit');
Route::post('/getPermitFiles', 'PermitController@getPermitFiles')->name('getPermitFiles');
Route::post('/submitPermit', 'PermitController@submitPermit')->name('submitPermit');
Route::post('/deletePermit', 'PermitController@deletePermit')->name('deletePermit');
Route::post('/submitPermitConfig', 'PermitController@submitPermitConfig')->name('submitPermitConfig');
Route::post('/loadPermitConfig', 'PermitController@loadPermitConfig')->name('loadPermitConfig');

//Custom Module
Route::get('/customModule', 'CustomEquipmentController@customModule')->name('customModule');
Route::post('/getCustomModules', 'CustomEquipmentController@getCustomModules')->name('getCustomModules');
Route::post('/submitModule', 'CustomEquipmentController@submitModule')->name('submitModule');
Route::post('/deleteModule', 'CustomEquipmentController@deleteModule')->name('deleteModule');
Route::post('/moduleToggleFavorite', 'CustomEquipmentController@moduleToggleFavorite')->name('moduleToggleFavorite');

//Custom Inverter
Route::get('/customInverter', 'CustomEquipmentController@customInverter')->name('customInverter');
Route::post('/getCustomInverters', 'CustomEquipmentController@getCustomInverters')->name('getCustomInverters');
Route::post('/submitInverter', 'CustomEquipmentController@submitInverter')->name('submitInverter');
Route::post('/deleteInverter', 'CustomEquipmentController@deleteInverter')->name('deleteInverter');
Route::post('/inverterToggleFavorite', 'CustomEquipmentController@inverterToggleFavorite')->name('inverterToggleFavorite');

//Custom Solar Racking(Rail Support)
Route::get('/customRacking', 'CustomEquipmentController@customRacking')->name('customRacking');
Route::post('/getCustomRacking', 'CustomEquipmentController@getCustomRacking')->name('getCustomRacking');
Route::post('/submitRacking', 'CustomEquipmentController@submitRacking')->name('submitRacking');
Route::post('/deleteRacking', 'CustomEquipmentController@deleteRacking')->name('deleteRacking');
Route::post('/rackingToggleFavorite', 'CustomEquipmentController@rackingToggleFavorite')->name('rackingToggleFavorite');

//Custom Stanchions
Route::get('/customStanchion', 'CustomEquipmentController@customStanchion')->name('customStanchion');
Route::post('/getCustomStanchion', 'CustomEquipmentController@getCustomStanchion')->name('getCustomStanchion');
Route::post('/submitStanchion', 'CustomEquipmentController@submitStanchion')->name('submitStanchion');
Route::post('/deleteStanchion', 'CustomEquipmentController@deleteStanchion')->name('deleteStanchion');
Route::post('/stanchionToggleFavorite', 'CustomEquipmentController@stanchionToggleFavorite')->name('stanchionToggleFavorite');

//Standard Module
Route::get('/standardModule', 'StandardEquipmentController@standardModule')->name('standardModule');
Route::post('/getStandardModules', 'StandardEquipmentController@getStandardModules')->name('getStandardModules');
Route::post('/submitStandardModule', 'StandardEquipmentController@submitModule')->name('submitStandardModule');
Route::post('/deleteStandardModule', 'StandardEquipmentController@deleteModule')->name('deleteStandardModule');
Route::post('/standardModuleToggleFavorite', 'StandardEquipmentController@moduleToggleFavorite')->name('standardModuleToggleFavorite');
Route::post('/copyStandardModules', 'StandardEquipmentController@copyModules')->name('copyStandardModules');
Route::post('/delStandardModules', 'StandardEquipmentController@delModules')->name('delStandardModules');

//Standard Inverter
Route::get('/standardInverter', 'StandardEquipmentController@standardInverter')->name('standardInverter');
Route::post('/getStandardInverters', 'StandardEquipmentController@getStandardInverters')->name('getStandardInverters');
Route::post('/submitStandardInverter', 'StandardEquipmentController@submitInverter')->name('submitStandardInverter');
Route::post('/deleteStandardInverter', 'StandardEquipmentController@deleteInverter')->name('deleteStandardInverter');
Route::post('/standardInverterToggleFavorite', 'StandardEquipmentController@inverterToggleFavorite')->name('inverterToggleFavorite');
Route::post('/copyStandardInverters', 'StandardEquipmentController@copyInverters')->name('copyStandardInverters');
Route::post('/delStandardInverters', 'StandardEquipmentController@delInverters')->name('delStandardInverters');

//Standard Solar Racking(Rail Support)
Route::get('/standardRacking', 'StandardEquipmentController@standardRacking')->name('standardRacking');
Route::post('/getStandardRacking', 'StandardEquipmentController@getStandardRacking')->name('getStandardRacking');
Route::post('/submitStandardRacking', 'StandardEquipmentController@submitRacking')->name('submitStandardRacking');
Route::post('/deleteStandardRacking', 'StandardEquipmentController@deleteRacking')->name('deleteStandardRacking');
Route::post('/standardRackingToggleFavorite', 'StandardEquipmentController@rackingToggleFavorite')->name('standardRackingToggleFavorite');
Route::post('/copyStandardRackings', 'StandardEquipmentController@copyRackings')->name('copyStandardRackings');
Route::post('/delStandardRackings', 'StandardEquipmentController@delRackings')->name('delStandardRackings');

//Standard Stanchions
Route::get('/standardStanchion', 'StandardEquipmentController@standardStanchion')->name('standardStanchion');
Route::post('/getStandardStanchion', 'StandardEquipmentController@getStandardStanchion')->name('getStandardStanchion');
Route::post('/submitStandardStanchion', 'StandardEquipmentController@submitStanchion')->name('submitStandardStanchion');
Route::post('/deleteStandardStanchion', 'StandardEquipmentController@deleteStanchion')->name('deleteStandardStanchion');
Route::post('/standardStanchionToggleFavorite', 'StandardEquipmentController@stanchionToggleFavorite')->name('standardStanchionToggleFavorite');
Route::post('/copyStandardStanchions', 'StandardEquipmentController@copyStanchions')->name('copyStandardStanchions');
Route::post('/delStandardStanchions', 'StandardEquipmentController@delStanchions')->name('delStandardStanchions');

//Two Factor Authentication
Route::get('verify/resend', 'Auth\TwoFactorController@resend')->name('verify.resend');
Route::resource('verify', 'Auth\TwoFactorController')->only(['index', 'store']);
Route::get('verify/blocked', 'Auth\TwoFactorController@blocked')->name('verify.blocked');
Route::get('verify/geolocation', 'Auth\TwoFactorController@geolocation')->name('verify.geolocation');
Route::get('/storeLocation/{id}', 'Auth\TwoFactorController@storeLocation');

// Seal Positioning
Route::get('/sealtemplate', 'CompanyController@sealtemplate')->name('sealtemplate');
Route::get('/sealassign', 'CompanyController@sealassign')->name('sealassign');
Route::get('/sealmanage', 'CompanyController@sealmanage')->name('sealmanage');
Route::post('/extractImgFromPDF', 'CompanyController@extractImgFromPDF')->name('extractImgFromPDF');
Route::post('/saveSealData', 'CompanyController@saveSealData')->name('saveSealData');
Route::post('/loadSealData', 'CompanyController@loadSealData')->name('loadSealData');
Route::post('/saveSealTemplate', 'CompanyController@saveSealTemplate')->name('saveSealTemplate');
Route::post('/editSealTemplate', 'CompanyController@editSealTemplate')->name('editSealTemplate');
Route::post('/getSealTemplateList', 'CompanyController@getSealTemplateList')->name('getSealTemplateList');
Route::post('/getTemplateData', 'CompanyController@getTemplateData')->name('getTemplateData');
Route::post('/delTemplate', 'CompanyController@deleteTemplate')->name('deleteTemplate');

// Pull System Msgs
Route::post('/getSystemMsgs', 'GeneralController@getSystemMsgs')->name('getSystemMsgs');