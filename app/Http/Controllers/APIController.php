<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use App\User;
use App\JobRequest;
use App\Company;
use App\DataCheck;
use App\BackupSetting;
use App\CustomModule;
use App\CustomInverter;
use App\CustomRacking;
use App\CustomStanchion;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Ifsnop\Mysqldump as IMysqldump;

use DateTime;
use DateTimeZone;

class APIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

    }

    /**
     * Return the list of projects in json
     *
     * @return JSON
     */
    public function getJobList(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                $query = new JobRequest;

                if(isset($request['dateFrom'])){
                    $date = new DateTime($request['dateFrom'], new DateTimeZone('EST'));
                    $date->setTimezone(new DateTimeZone('UTC'));
                    $query = $query->where('submittedTime', '>=', $date->format("Y-m-d H:i:s"));
                }
                if(isset($request['dateTo']))
                {
                    $dateTo = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($request['dateTo'])));
                    $date = new DateTime($dateTo, new DateTimeZone('EST'));
                    $date->setTimezone(new DateTimeZone('UTC'));
                    $query = $query->where('submittedTime', '<=', $date->format("Y-m-d H:i:s"));
                }
                if(isset($request['clientIdFrom']))
                    $query = $query->where('companyId', '>=', $request['clientIdFrom']);
                if(isset($request['clientIdTo']))
                    $query = $query->where('companyId', '<=', $request['clientIdTo']);
                if(isset($request['company']))
                    $query = $query->where('companyName', '=', $request['company']);

                $data = $query->get();
                if(isset($request['sortAlphabetical']) && ($request['sortAlphabetical'] == 'false' || $request['sortAlphabetical'] == 'False' || $request['sortAlphabetical'] == 'FALSE'))
                    $data = $data->sortBy('clientProjectNumber', SORT_REGULAR, true)->values();
                else
                    $data = $data->sortBy('clientProjectNumber', SORT_REGULAR, false)->values();
                foreach($data as $job){
                    $job['createdTime'] = date('Y-m-d H:i:s', strtotime('-5 hour',strtotime($job['createdTime'])));
                    $job['submittedTime'] = date('Y-m-d H:i:s', strtotime('-5 hour',strtotime($job['submittedTime'])));
                }
                
                return response()->json(['success' => true, 'message' => 'Success', 'data' => $data]);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Update Project Data
     *
     * @return JSON
     */
    public function updateJobData(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                if(isset($request['requestFile']))
                {
                    $job = JobRequest::where('requestFile', $request['requestFile'])->first();
                    if($job){
                        $jobCols = Schema::getColumnListing('job_request');
                        foreach($jobCols as $column){
                            if($column != 'requestFile' && isset($request[$column]))
                                $job[$column] = $request[$column];
                        }
                        $update = false;
                        $dataCheckCols = Schema::getColumnListing('data_check');
                        foreach($dataCheckCols as $column){
                            if(isset($request[$column]))
                                $update = true;
                        }
                        if($update){
                            $dataCheck = DataCheck::firstOrNew(array('jobId' => $job['id']));
                            foreach($dataCheckCols as $column){
                                if($column != 'jobId' && isset($request[$column]))
                                    $dataCheck[$column] = $request[$column];
                            }
                            $dataCheck->save();
                        }
                        if($job->save())
                            return response()->json(['success' => true, 'message' => 'Success']);
                        else
                            return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Database Error.']);
                    }
                    else
                        return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Cannot find file.']);
                }
                else
                    return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Wrong File Name.']);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Download Project JSON File
     *
     * @return JSON
     */
    public function downloadFile(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                if(isset($request['fileName'])){
                    $job = JobRequest::where('requestFile', $request['fileName'])->first();
                    if($job){
                        $company = Company::where('id', $job->companyId)->first();
                        $companyNumber = $company ? $company['company_number'] : 0;
                        $folderPrefix = $companyNumber. '. ' . $job['companyName'] . '/';
                        if( Storage::disk('input')->exists("/" . $folderPrefix . $request['fileName']) )
                            return response()->download(storage_path('/input/' . $folderPrefix . $request['fileName']), null, ['Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0']);
                        else
                            return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Cannot find file.']);    
                    }
                    else 
                        return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Wrong File Name.']);
                }
                else
                    return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Wrong File Name.']);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Get User and Client Admin Email / Name
     *
     * @return JSON
     */
    public function getUserInfo(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                $data = array();
                if(isset($request['companyId'])){
                    $data['ClientAdmin'] = User::select('username', 'email')->where('userrole', '=', '1')->where('companyid', '=', $request['companyId'])->first();
                }
                else
                    return response()->json(['success' => false, 'message' => 'Fail', 'message' => 'Wrong File Name.']);

                if(isset($request['userId'])){
                    $data['Client'] = User::select('username', 'email')->where('usernumber', '=', $request['userId'])->where('companyid', '=', $request['companyId'])->first();
                }
                return response()->json(['success' => true, 'data' => $data]);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Return the list of company
     *
     * @return JSON
     */
    public function getCompanyList(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                $data = Company::all();
                if(isset($request['sortAlphabetical']) && ($request['sortAlphabetical'] == 'false' || $request['sortAlphabetical'] == 'False' || $request['sortAlphabetical'] == 'FALSE'))
                    $data = $data->sortBy('company_name', SORT_REGULAR, true)->values();
                else
                    $data = $data->sortBy('company_name', SORT_REGULAR, false)->values();
                
                return response()->json(['success' => true, 'message' => 'Success', 'data' => $data]);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Backup DB and upload to dropbox by daily cron job.
     *
     * @return JSON
     */
    public function cronDBBackup(Request $request){
        $settingData = BackupSetting::first();
        $day_of_week = date('N', time());
        if(!$settingData || $settingData->backup_days == '-1' || in_array($day_of_week - 1, explode(",", $settingData->backup_days))){
            try {
                $dump = new IMysqldump\Mysqldump('mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'), ['add-drop-table' => true]);
                $filename = env('DB_DATABASE') . '_' . time() . '.sql';
                $dump->start(storage_path('/db/') . $filename);

                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                $dropboxFile = new DropboxFile(storage_path('/db/') . $filename);
                $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_DB_BACKUP') . $filename, ['mode' => 'overwrite']);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        } else 
            return response()->json(['success' => false, 'message' => 'Today is not a cron setted day.']);
    }

    /**
     * Return Custom Equipment Data according to two filters: type & crc32.
     *
     * @return JSON
     */
    public function getCustomEquipment(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                if(isset($request['type'])){
                    if($request['type'] == 'module')
                        $handler = new CustomModule;
                    else if($request['type'] == 'inverter')
                        $handler = new CustomInverter;
                    else if($request['type'] == 'racking')
                        $handler = new CustomRacking;
                    else if($request['type'] == 'stanchion')
                        $handler = new CustomStanchion;
                    else
                        return response()->json(['success' => false, 'message' => 'Wrong Type.']);

                    if(isset($request['crc32']))
                        $handler = $handler->where('crc32', $request['crc32']);
                    
                    $data = $handler->get();
                    return response()->json(['success' => true, 'data' => $data]);
                } else 
                    return response()->json(['success' => false, 'message' => 'Type Required.']);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }
}
