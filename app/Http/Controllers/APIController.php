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
use App\PVModuleCEC;
use App\PVModule;
use App\PVInverter;
use App\Stanchion;
use App\RailSupport;
use App\CustomModule;
use App\CustomInverter;
use App\CustomRacking;
use App\CustomStanchion;
use App\JobChat;
use App\BillingHistory;
use App\BillingInfo;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Ifsnop\Mysqldump as IMysqldump;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Illuminate\Filesystem\Filesystem;

use DateTime;
use DateTimeZone;
use Mail;
use DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use ZipArchive;

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
                            if($column != 'requestFile' && !empty($request->input($column)))
                                $job[$column] = $request->input($column);
                        }
                        $update = false;
                        $dataCheckCols = Schema::getColumnListing('data_check');
                        foreach($dataCheckCols as $column){
                            if(!empty($request->input($column)))
                                $update = true;
                        }
                        if($update){
                            $dataCheck = DataCheck::where('jobId', $job['id'])->first();
                            if(!$dataCheck){
                                $dataCheck = DataCheck::create([
                                    'jobId' => $job['id'],
                                    'collarHeights' => null
                                ]);
                            }
                            foreach($dataCheckCols as $column){
                                if($column != 'jobId' && !empty($request->input($column)))
                                    $dataCheck[$column] = $request->input($column);
                            }
                            $dataCheck->save();
                            $today = new DateTime('now', new DateTimeZone('UTC'));
                            $today->setTimezone(new DateTimeZone('EST'));
                            Storage::disk('local')->append('ping_'.$request['requestFile'], $request->fullUrl() . ": " . $today->format("Y-m-d H:i:s") . "\n");
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
                    $clients = User::select('username', 'email')->where('userrole', '=', '1')->where('companyid', '=', $request['companyId'])->get();
                    $usernames = array();
                    $emails = array();
                    foreach($clients as $client){
                        array_push($usernames, $client->username);
                        array_push($emails, $client->email);
                    }
                    $data['ClientAdmin'] = array("username" => implode("; ", $usernames), "email" => implode("; ", $emails));
                }
                else
                    return response()->json(['success' => false, 'message' => 'Fail', 'message' => 'Wrong Company Id.']);

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

    /**
     * Add chat to job_chat table automatically
     *
     * @return JSON
     */
    public function addChat(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                if(isset($request['message']) && isset($request['jobId'])){
                    $job = JobRequest::where('id', $request['jobId'])->first();
                    if($job){
                        JobChat::create([
                            'jobId' => $request['jobId'],
                            'userId' => $user->id,
                            'text' => $request['message']
                        ]);
                        
                        if(isset($request['chatIcon'])){
                            $job->chatIcon = $request['chatIcon'];
                            $job->save();
                        } else {
                            $job->chatIcon = 2;
                            $job->save();
                        }

                        return response()->json(['success' => true]);
                    } else 
                        return response()->json(['success' => false, 'message' => 'Cannot find the project.']);
                } else 
                    return response()->json(['success' => false, 'message' => 'Wrong Parameters.']);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Get chat history by job_id
     *
     * @return JSON
     */
    public function getChat(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                if(isset($request['jobId'])){
                    $job = JobRequest::where('id', $request['jobId'])->first();
                    if($job){
                        $messages = JobChat::leftjoin('users', "users.id", "=", "job_chat.userId")
                        ->where('job_chat.jobId', $request['jobId'])
                        ->orderBy('job_chat.id', 'desc')
                        ->get(array('users.username as username', 'users.userrole as userrole', 'job_chat.text as text', 'job_chat.datetime as datetime'));
                        return response()->json(['success' => true, 'data' => $messages]);
                    } else 
                        return response()->json(['success' => false, 'message' => 'Cannot find the project.']);
                } else 
                    return response()->json(['success' => false, 'message' => 'Wrong Parameters.']);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }
    /**
     * Retrieving CSRF Token
     */
    public function getCsrfToken(Request $request) {
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if ($user) return response()->json(['success' => true, 'token' => csrf_token()]);
        }
    }
    /**
     * Update the chat history of the project.
     *
     * @return JSON
     */
    public function sendEmail(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if( isset($request['to']) && isset($request['title']) && isset($request['text']) )
            {
                $data = ['cc' => $request['cc'], 'bcc' => $request['bcc'], 'subject'=> $request['subject'], 'title' => $request['title'], 'text' => $request['text'], 'signature' => $request['signature']];
                $to = $request['to'];
                $from = $request['from'];
                
                $cc = explode(",", $request['cc']);
                $bcc = explode(",", $request['bcc']);
                $subject = $request['subject'];
                $title = $request['title'];

                $ccUser = User::where('email', $cc)->first();
                if($ccUser && $ccUser->allow_cc == 0)
                    $cc = null;
                
                $bccUser = User::where('email', $bcc)->first();
                if($bccUser && $bccUser->allow_cc == 0)
                    $bcc = null;
                
                // print_r($request['bcc']);
                // print_r($request['subject']);
                // print_r($request['title']);
                
                Mail::send('mail.admintemplate', $data, function ($m) use ($to, $from, $cc, $bcc, $subject, $title) {
                    if ($cc) {
                        if($bcc){
                            $m->from($from, $title)->to($to)->cc($cc)->bcc($bcc)->subject($subject);
                        } else {
                            $m->from($from, $title)->to($to)->cc($cc)->subject($subject);
                        }
                    } else {
                        if($bcc){
                            $m->from($from, $title)->to($to)->bcc($bcc)->subject($subject);
                        } else {
                            $m->from($from, $title)->to($to)->subject($subject);
                        }
                    }
                    
                });
                
                // exit;
                return response()->json(['success' => true, 'message'=> 'successfully sent!']);
            }
            else
                return response()->json(['success' => false, 'message' => 'No To User or No Title or No Text'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }
    /**
     * Update the chat history of the project.
     *
     * @return JSON
     */
    public function updateChat(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                if($request['chatId'])
                {
                    $chatItem = JobChat::where('id', '=', $request['chatId'])->first();
                    if($chatItem)
                    {
                        if( isset($request['text']) )
                        {
                            $chatItem->text = $request['text'];
                            $chatItem->save();
                            return response()->json(['success' => true]);
                        }
                        else
                            return response()->json(['success' => false] );
                    }
                    else
                        return response()->json(['success' => false, 'message' => 'Cannot find the chat.'] );
                }
                else
                    return response()->json(['success' => false, 'message' => 'Wrong Chat Id.'] );
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }
    /**
     * Delete Chat
     *
     * @return JSON
     */
    function delChat(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2) {
                if($request['chatId'])
                {
                    $res = JobChat::where('id', $request['chatId'])->delete();
                    return response()->json(['success' => true]);
                }
                else
                    return response()->json(['success' => false] );
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Remove special characters on dropbox filenames
     *
     * @return JSON
     */
    function removeSpecialChars(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2) {
                if(isset($request['idFrom']) && isset($request['idTo'])){
                    for($id = $request['idFrom']; $id <= $request['idTo']; $id ++){
                        $job = JobRequest::where('id', $id)->first();
                        if($job){
                            $company = Company::where('id', $job['companyId'])->first();
                            $companyNumber = $company ? $company['company_number'] : 0;
                            $folderPrefix = '/' . $companyNumber . ". " . $job['companyName'] . '/';

                            $filepath = $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'];
                            $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                            $dropbox = new Dropbox($app);
                            try{
                                $this->iterateFolder($dropbox, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath . '/', $id);
                            } catch (DropboxClientException $e) { 
                                echo 'error while iterate IN (jobId: '. $id . ') <br>';
                            }
                            try{
                                $this->iterateFolder($dropbox, env('DROPBOX_PROJECTS_PATH') . '/IN_copy' . $filepath . '/', $id);
                            } catch (DropboxClientException $e) { 
                                echo 'error while iterate IN_copy (jobId: '. $id . ') <br>';
                            }
                            try{
                                $this->iterateFolder($dropbox, env('DROPBOX_PROJECTS_PATH') . '/eSealed' . $filepath . '/', $id);
                            } catch (DropboxClientException $e) { 
                                echo 'error while iterate eSealed (jobId: '. $id . ') <br>';
                            }
                        }
                    }
                    echo 'DONE! <br>';
                } else
                    return response()->json(['success' => false, 'message' => 'Wrong parameter.']);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Iterate all the subdirectories and files to remove special characters from the dropbox.
     *
     * @return Object
     */
    public function iterateFolder(Dropbox $dropbox, String $folderPath, String $id){
        $listFolderContents = $dropbox->listFolder($folderPath);
        $files = $listFolderContents->getItems()->all();
        foreach($files as $file){
            if($file->getDataProperty('.tag') === 'file')
            {
                if(preg_match('/[;:#&@]/', $file->getName())){
                    $newname = str_replace(array(":",";", "#", "&", "@", "/"), array(""), $file->getName());
                    echo 'jobId: ' . $id . '  ' . $folderPath . $file->getName() . ' -> ' . $folderPath . $newname . ' <br>';
                    $dropbox->move($folderPath . $file->getName(), $folderPath . $newname);
                }
            }
            else
                $this->iterateFolder($dropbox, $folderPath . $file->getName() . '/', $id);
        }
    }

    /**
     * Check if today is billing day. If it is, calculates the jobs and send payment
     *
     * @return JSON
     */
    public function cronSendBill(Request $request){
        $companies = Company::get();

        $weekday = date('w');
        if($weekday == 0) // sunday is 7 in our system
            $weekday = 7;
        $monthday = date('j');
        $curtime = time();

        foreach($companies as $company){
            $billInfo = BillingInfo::where('clientId', $company->id)->first();
            if($billInfo){
            // if($billInfo && $billInfo->card_number != '' && $billInfo->expiration_date != '' && $billInfo->security_code != ''){
                // Check if today is the payment day
                $checkDay = false;
                if($billInfo->billing_period == 0 && $weekday == $billInfo->billing_day){ // weekly
                    $checkDay = true;
                    $dateFrom = date('Y-m-d', strtotime('-7 day', $curtime));
                    $dateTo = date('Y-m-d', strtotime('-1 day', $curtime));
                    $timeFrom = date('Y-m-d', strtotime('-7 day', $curtime)) . ' 00:00:00';
                    $timeTo = date('Y-m-d', strtotime('-1 day', $curtime)) . ' 23:59:59';
                } else if($billInfo->billing_period == 1 && $weekday == $billInfo->billing_day){ // biweekly
                    $lastbilled = BillingHistory::where('companyId', $company->id)->where('state', '!=', 3)->orderBy('issuedAt', 'desc')->first();
                    if(!$lastbilled)
                        $checkDay = true;
                    else{
                        $diff = abs(time() - strtotime($lastbilled->issuedAt));
                        if($diff > 10 * 60 * 60 * 24) { // Check if it's past 10 days from last billing day
                            $checkDay = true;
                        }
                    }
                    if($checkDay == true){
                        $dateFrom = date('Y-m-d', strtotime('-14 day', $curtime));
                        $dateTo = date('Y-m-d', strtotime('-1 day', $curtime));
                        $timeFrom = date('Y-m-d', strtotime('-14 day', $curtime)) . ' 00:00:00';
                        $timeTo = date('Y-m-d', strtotime('-1 day', $curtime)) . ' 23:59:59';
                    }
                } if($billInfo->billing_period == 2 && $monthday == $billInfo->billing_day){ // monthly
                    $checkDay = true;
                    $dateFrom = date('Y-m-d', strtotime('-1 month', $curtime));
                    $dateTo = date('Y-m-d', strtotime('-1 day', $curtime));
                    $timeFrom = date('Y-m-d', strtotime('-1 month', $curtime));
                    $timeTo = date('Y-m-d', strtotime('-1 day', $curtime)) . ' 23:59:59';
                } 

                if($checkDay) { // Bill only if today is bill day
                    // collect the jobs that need to be billed
                    if($billInfo->billing_type == 0){
                        $jobs = JobRequest::leftjoin('users', function($join){
                            $join->on('job_request.companyId', '=', 'users.companyid');
                            $join->on('job_request.userId', '=', 'users.usernumber');
                        })
                        ->where('job_request.companyId', $company->id)->where('job_request.billed', '0')->where('job_request.projectState', '9')
                        ->orderBy('job_request.createdTime', 'asc')
                        ->get(
                            array('job_request.id as id', 'users.username as username', 'job_request.clientProjectName as projectname', 'job_request.clientProjectNumber as projectnumber', 'job_request.state as state', 'job_request.createdTime as createdtime', 'job_request.submittedTime as submittedtime')
                        );
                    }
                    else{
                        $jobs = JobRequest::leftjoin('users', function($join){
                            $join->on('job_request.companyId', '=', 'users.companyid');
                            $join->on('job_request.userId', '=', 'users.usernumber');
                        })
                        ->where('job_request.companyId', $company->id)->where('job_request.billed', '0')->where('job_request.createdTime', '>=', $timeFrom)->where('job_request.createdTime', '<=', $timeTo)
                        ->orderBy('job_request.createdTime', 'asc')
                        ->get(
                            array('job_request.id as id', 'users.username as username', 'job_request.clientProjectName as projectname', 'job_request.clientProjectNumber as projectnumber', 'job_request.state as state', 'job_request.createdTime as createdtime', 'job_request.submittedTime as submittedtime')
                        );
                    }
                    
                    if(count($jobs) > 0){
                        // calculate this month's billed jobs count
                        $month = date('m');
                        $histories = BillingHistory::where('companyId', $company->id)->whereRaw('Month(issuedAt) = '.$month)->where('state', 2)->get();
                        $billedCount = 0;
                        foreach($histories as $history)
                            $billedCount += $history->jobCount;
                        
                        // calcs the number of not exceeded job counts, exceeded job counts in the month
                        if($billedCount >= $billInfo->expected_jobs){
                            $notExceeded = 0;
                            $exceeded = count($jobs);
                        } else {
                            $notExceeded = min($billInfo->expected_jobs - $billedCount, count($jobs));
                            $exceeded = count($jobs) - $notExceeded;
                        }
                        $amount = $billInfo->base_fee * $notExceeded + $billInfo->extra_fee * $exceeded;

                        $curBill = new BillingHistory();
                        $curBill->companyId = $company->id;
                        $curBill->amount = $amount;
                        $jobIds = array();
                        foreach($jobs as $job)
                            $jobIds[] = $job->id;
                        $curBill->jobIds = json_encode($jobIds);
                        $curBill->jobCount = count($jobs);
                        $curBill->issuedAt = gmdate("Y-m-d\TH:i:s", $curtime);
                        // if($billInfo->billing_type == 1)
                        $curBill->issuedFrom = $dateFrom;
                        $curBill->issuedTo = $dateTo;
                        $curBill->state = 0;
                        $curBill->notExceeded = $notExceeded;
                        $curBill->exceeded = $exceeded;
                        $curBill->duedate = date('Y-m-d', strtotime("+{$billInfo->due_days} day", $curtime));

                        $expenses = array();
                        array_push($expenses, ["date" => gmdate("Y-m-d", $curtime), "code" => "Monthly Base", "description" => "Monthly base number of jobs", "quantity" => $notExceeded, "price" => $billInfo->base_fee, "amount" => $billInfo->base_fee * $notExceeded]);
                        array_push($expenses, ["date" => gmdate("Y-m-d", $curtime), "code" => "Above Monthly", "description" => "Jobs exceeding monthly base", "quantity" => $exceeded, "price" => $billInfo->extra_fee, "amount" => $billInfo->extra_fee * $exceeded]);
                        $curBill->expenses = json_encode($expenses);

                        $curBill->save();

                        if($billInfo->send_invoice == 0){ // Directly authorize and charge funds
                            $this->createInvoice(0, $curBill, $company, $billInfo, $jobs, $curtime); // Unpaid invoice
                            $this->chargeCreditCard($curBill, $company, $billInfo, $amount, $jobs, $curtime);
                        } else if($billInfo->send_invoice == 1) { // Send unpaid invoice first
                            $this->createInvoice(0, $curBill, $company, $billInfo, $jobs, $curtime); // Unpaid invoice
                            $this->sendBillMail(0, $curBill, $company, $billInfo, '');
                        }
                    }
                }
            }
        }
    }

    /**
     * Create the invoice and set the invoice filename to invoice field.
     *
     * @return Boolean
     */
    private function createInvoice($type, $curBill, $company, $billInfo, $jobs, $curtime){
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new DOMPDF($options);
        $dompdf->setPaper('A4', 'portrait');
        
        $expenses = array();
        if($curBill->expenses)
            $expenses = json_decode($curBill->expenses);
        
        $html = view('pdf.invoice')
                ->with('type', $type)
                ->with('curBill', $curBill)
                ->with('invoiceDate', gmdate("Y-m-d", $curtime))
                ->with('company', $company)
                ->with('billInfo', $billInfo)
                ->with('dueDate', $curBill->duedate)
                ->with('jobs', $jobs)
                ->with('expenses', $expenses)
                ->render();

        $dompdf->load_html($html);
        $dompdf->render();

        // Parameters
        $x          = 545;
        $y          = 810;
        $text       = "{PAGE_NUM} of {PAGE_COUNT}";     
        $font       = $dompdf->getFontMetrics()->get_font('Helvetica', 'normal');   
        $size       = 10;    
        $color      = array(0,0,0);
        $word_space = 0.0;
        $char_space = 0.0;
        $angle      = 0.0;

        $dompdf->getCanvas()->page_text(
        $x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle
        );

        $output = $dompdf->output();

        $filepath = storage_path('invoice') . '/' . $company->company_number . '. '. $company->company_name . ' ' . $curtime . '.pdf';
        file_put_contents($filepath, $output);

        $curBill->invoice = $company->company_number . '. '. $company->company_name . ' ' . $curtime . '.pdf';
        $curBill->save();

        $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
        $dropbox = new Dropbox($app);
        $dropboxFile = new DropboxFile(storage_path('invoice') . '/' . $company->company_number . '. '. $company->company_name . ' ' . $curtime . '.pdf');
        $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_INVOICE_BACKUP') . $company->company_number . '. '. $company->company_name . '/' . $company->company_number . '. '. $company->company_name . ' ' . $curtime . '.pdf', ['mode' => 'overwrite']);
    }

    /**
     * Send bill notification to client / supers
     *
     * @return Boolean
     */
    private function sendBillMail($type, $curBill, $company, $billInfo, $error = ''){
        $data = ['type' => $type, 'curBill' => $curBill, 'company' => $company, 'cardnumber' => substr($billInfo->card_number, -4), 'issuedDate' => date('Y-m-d', strtotime($curBill->issuedAt)),'error' => $error];
        
        if($company->bill_notifiers){
            $notifiers = explode(";", str_replace(' ', '', $company->bill_notifiers));
            foreach($notifiers as $notifier){
                if($notifier != ''){
                    $info = ['email' => $notifier, 'filename' => $curBill->invoice];
                    Mail::send('mail.billnotification', $data, function ($m) use ($info) {
                        $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($info['email'])->subject('Important! iRoof Bill Notification.');
                        $m->attach(storage_path('invoice') . '/' . $info['filename']);
                    });
                }
            }
        }

        $supers = User::where('userrole', 2)->get();
        foreach($supers as $super){
            $info = ['email' => $super->email, 'filename' => $curBill->invoice];
            Mail::send('mail.billsupernotify', $data, function ($m) use ($info) {
                $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($info['email'])->subject('Important! iRoof Bill Notification.');
                $m->attach(storage_path('invoice') . '/' . $info['filename']);
            });
        }
    }

    /**
     * Authorize and Capture credit card payments
     *
     * @return Authorize.Net Response
     */
    private function chargeCreditCard($curBill, $company, $billInfo, $amount, $jobs, $curtime)
    {
        echo "Processing credit card payments for " . $company->company_number . ". " . $company->company_name . " with historyId: " . $curBill->id . "\n";
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        
        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber(str_replace(' ', '', $billInfo->card_number));
        $creditCard->setExpirationDate($billInfo->expiration_date);
        $creditCard->setCardCode($billInfo->security_code);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create order information
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($curBill->id);
        $order->setDescription("iRoof Engineering Services");

        if($billInfo->billing_name != ''){
            // Set the customer's Bill To address
            $customerAddress = new AnetAPI\CustomerAddressType();
            $customerAddress->setFirstName($billInfo->billing_name);
            $customerAddress->setCompany($company->legal_name);
            $customerAddress->setAddress($billInfo->billing_address);
            $customerAddress->setCity($billInfo->billing_city);
            $customerAddress->setState($billInfo->billing_state);
            $customerAddress->setZip($billInfo->billing_zip);
            $customerAddress->setCountry("USA");
        }

        if($billInfo->shipping_name != ''){
            // Set the customer's Ship To address
            $shipAddress = new AnetAPI\CustomerAddressType();
            $shipAddress->setFirstName($billInfo->shipping_name);
            $shipAddress->setCompany("Princeton Engineering");
            $shipAddress->setAddress($billInfo->shipping_address);
            $shipAddress->setCity($billInfo->shipping_city);
            $shipAddress->setState($billInfo->shipping_state);
            $shipAddress->setZip($billInfo->shipping_zip);
            $shipAddress->setCountry("USA");
        }

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($company->company_number);
        $customerData->setEmail($company->company_email);

        // Add values for transaction settings
        // $duplicateWindowSetting = new AnetAPI\SettingType();
        // $duplicateWindowSetting->setSettingName("duplicateWindow");
        // $duplicateWindowSetting->setSettingValue("60");

        // Add some merchant defined fields. These fields won't be stored with the transaction,
        // but will be echoed back in the response.
        // $merchantDefinedField1 = new AnetAPI\UserFieldType();
        // $merchantDefinedField1->setName("customerLoyaltyNum");
        // $merchantDefinedField1->setValue("1128836273");

        // $merchantDefinedField2 = new AnetAPI\UserFieldType();
        // $merchantDefinedField2->setName("favoriteColor");
        // $merchantDefinedField2->setValue("blue");

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);
        if($billInfo->billing_name)
            $transactionRequestType->setBillTo($customerAddress);
        if($billInfo->shipping_name)
            $transactionRequestType->setBillTo($shipAddress);
        $transactionRequestType->setCustomer($customerData);
        // $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
        // $transactionRequestType->addToUserFields($merchantDefinedField1);
        // $transactionRequestType->addToUserFields($merchantDefinedField2);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();
            
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    echo " Successfully created transaction with";
                    $curBill->response = " Transaction ID: " . $tresponse->getTransId() . "\n";
                    $curBill->response .= (" Transaction Response Code: " . $tresponse->getResponseCode() . "\n");
                    $curBill->response .= (" Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n");
                    $curBill->response .= (" Auth Code: " . $tresponse->getAuthCode() . "\n");
                    $curBill->response .= (" Description: " . $tresponse->getMessages()[0]->getDescription() . "\n");
                    echo $curBill->response;

                    $curBill->state = 2;
                    $curBill->save();

                    $this->setBilled($curBill);
                    $this->createInvoice(1, $curBill, $company, $billInfo, $jobs, $curtime); // Paid invoice
                    $this->sendBillMail(2, $curBill, $company, $billInfo, '');
                } else {
                    echo "Transaction Failed \n";
                    $error = '';
                    if ($tresponse->getErrors() != null) {
                        $curBill->response = " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        $curBill->response .= (" Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n");
                        $error = $tresponse->getErrors()[0]->getErrorText();
                        echo $curBill->response;
                    }
                    $curBill->state = 1;
                    $curBill->save();
                    $this->sendBillMail(1, $curBill, $company, $billInfo, $error);
                }
                // Or, print errors if the API request wasn't successful
            } else {
                echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
            
                $error = '';
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $curBill->response = " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    $curBill->response .= (" Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n");
                    $error = $tresponse->getErrors()[0]->getErrorText();
                    echo $curBill->response;
                } else {
                    $curBill->response = " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    $curBill->response .= (" Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n");
                    $error = $response->getMessages()->getMessage()[0]->getText();
                    echo $curBill->response;
                }
                $curBill->state = 1;
                $curBill->save();
                $this->sendBillMail(1, $curBill, $company, $billInfo, $error);
            }
        } else {
            echo  "No response returned \n";
        }

        return $response;
    }

    /**
     * Set job_request jobs' billed to 1
     *
     * @return Boolean
     */
    private function setBilled($history){
        if($history && $history->jobIds){
            $jobIds = json_decode($history->jobIds);
            foreach($jobIds as $id){
                $job = JobRequest::where('id', $id)->first();
                if($job){
                    $job->billed = 1;
                    $job->save();
                }
            }
            return true;
        } else
            return false;
    }

    /**
     * Delete All unnecessary storage files
     *
     * @return JSON
     */
    public function cronStorageDelete() {
        $file = new Filesystem;
        try{
            $file->cleanDirectory(storage_path('invoice'));
            $file->cleanDirectory(storage_path('output'));
            $file->cleanDirectory(storage_path('report'));
            $file->cleanDirectory(storage_path('upload'));
            $file->cleanDirectory(storage_path('download'));
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    private function externalAPINotify($message, $company, $user, $success, $disableCompany = false) {
        $title = ($success == 1 ? 'iRoof API Request Success' : 'iRoof API Request Failed');
        $data = array('title' => $title, 'msg' => $message);
        if($company && $disableCompany) {
            $clients = User::where('userrole', 1)->where('companyid', $company->id)->get();
            foreach($clients as $client) {
                $info = array('email' => $client->email, 'title' => $title);
                Mail::send('mail.apiresponse', $data, function ($m) use ($info) {
                    $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($info['email'])->subject($info['title']);
                });
            }
        }

        if($user) {
            $info = array('email' => $user->email, 'title' => $title);
            Mail::send('mail.apiresponse', $data, function ($m) use ($info) {
                $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($info['email'])->subject($info['title']);
            });
        }

        $data['msg'] = $data['msg'] . " ({$company->company_name})";
        $supers = User::where('userrole', 2)->get();
        foreach($supers as $super) {
            $info = array('email' => $super->email, 'title' => $title);
            Mail::send('mail.apiresponse', $data, function ($m) use ($info) {
                $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($info['email'])->subject($info['title']);
            });
        }
    }

    /**
     * Create a new Job and saves JSON
     *
     * @return JSON
     */
    public function createJob(Request $request) {
        if(isset($request['CompanyId']) && isset($request['APIKey'])){
            $company = Company::where('id', '=', $request['CompanyId'])->where('api_key', '=', $request['APIKey'])->first();
            if($company) {
                if(isset($request['UserNumber'])) {
                    $user = User::where('companyid', $company['id'])->where('usernumber', $request['UserNumber'])->first();
                    if($user) {
                        if( Storage::disk('input')->exists("sample.json") ) {
                            $data = json_decode(Storage::disk('input')->get("sample.json"), true);
        
                            // Project Number
                            $maxProject = DB::select(DB::raw('select max(convert(clientProjectNumber, signed integer)) as maxNumber from job_request where companyId=' . $company->id))[0];
                            if($maxProject)
                                $projectNum = $maxProject->maxNumber + 1;
                            else
                                $projectNum = 1;
        
                            $data['ProgramData']['Version'] = array("Rev" => "3.0.0", "RevDate" => gmdate("m/d/Y", time()));
        
                            //CompanyInfo
                            $data['CompanyInfo']['Name'] = $company['company_name'];
                            $data['CompanyInfo']['Number'] = $company['company_number'];
                            $data['CompanyInfo']['UserId'] = $company['company_number'] . "." . ($user ? $user['usernumber'] : $user->usernumber);
                            $data['CompanyInfo']['Username'] = $user ? $user['username'] : $user->username;
                            $data['CompanyInfo']['UserEmail'] = $user ? $user['email'] : $user->email;
        
                            if(isset($request['ProjectInfo'])) {
                                $keys = array('City', 'Name', 'State', 'Street', 'Type', 'Zip');
                                foreach($keys as $key) {
                                    if(!isset($request['ProjectInfo'][$key])) {
                                        $this->externalAPINotify('ProjectInfo->' . $key . ' is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'ProjectInfo->' . $key . ' is missing.']);
                                    }
                                }

                                $data['ProjectInfo'] = $request['ProjectInfo'];
                            } else {
                                $this->externalAPINotify('ProjectInfo is missing.', $company, null, 0);
                                return response()->json(['success' => false, 'message' => 'ProjectInfo is missing.']);
                            }

                            if(isset($request['Personnel'])) {
                                $keys = array('DateOfFieldVisit', 'DateOfPlanSet', 'Name');
                                foreach($keys as $key) {
                                    if(!isset($request['Personnel'][$key])) {
                                        $this->externalAPINotify('Personnel->' . $key . ' is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Personnel->' . $key . ' is missing.']);
                                    }
                                }

                                $data['Personnel'] = $request['Personnel'];
                            } else {
                                $this->externalAPINotify('Personnel is missing.', $company, null, 0);
                                return response()->json(['success' => false, 'message' => 'Personnel is missing.']);
                            }
                            
                            if(isset($request['BuildingAge']))
                                $data['BuildingAge'] = $request['BuildingAge'];
                            else {
                                $this->externalAPINotify('BuildingAge is missing.', $company, null, 0);
                                return response()->json(['success' => false, 'message' => 'BuildingAge is missing.']);
                            }

                            if(isset($request['Equipment'])) {
                                if(isset($request['Equipment']['PVModule'])) {
                                    if(!isset($request['Equipment']['PVModule']['Type'])) {
                                        $this->externalAPINotify('Equipment->PVModule->Type is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVModule->Type is missing.']);
                                    } else if(!isset($request['Equipment']['PVModule']['SubType'])) {
                                        $this->externalAPINotify('Equipment->PVModule->SubType is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVModule->SubType is missing.']);
                                    }

                                    $module = PVModule::where('mfr', $request['Equipment']['PVModule']['Type'])->where('model', $request['Equipment']['PVModule']['SubType'])->first();
                                    $cecModule = PVModuleCEC::where('mfr', $request['Equipment']['PVModule']['Type'])->where('model', $request['Equipment']['PVModule']['SubType'])->first();
                                    $customModule = CustomModule::where('mfr', $request['Equipment']['PVModule']['Type'])->where('model', $request['Equipment']['PVModule']['SubType'])->where('client_no', $company['id'])->first();
                                    if(!$module && !$cecModule && !$customModule) {
                                        $this->externalAPINotify('Equipment->PVModule cannot be found on our module records.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVModule cannot be found on our module records.']);
                                    }
                                } else {
                                    return response()->json(['success' => false, 'message' => 'Equipment->PVModule is missing.']);
                                }

                                if(isset($request['Equipment']['PVInverter'])) {
                                    if(!isset($request['Equipment']['PVInverter']['Type'])) {
                                        $this->externalAPINotify('Equipment->PVInverter->Type is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVInverter->Type is missing.']);
                                    } else if(!isset($request['Equipment']['PVInverter']['SubType'])) {
                                        $this->externalAPINotify('Equipment->PVInverter->SubType is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVInverter->SubType is missing.']);
                                    }

                                    $inverter = PVInverter::where('module', $request['Equipment']['PVInverter']['Type'])->where('submodule', $request['Equipment']['PVInverter']['SubType'])->first();
                                    $customInverter = CustomInverter::where('mfr', $request['Equipment']['PVInverter']['Type'])->where('model', $request['Equipment']['PVInverter']['SubType'])->where('client_no', $company['id'])->first();
                                    if(!$inverter && !$customInverter) {
                                        $this->externalAPINotify('Equipment->PVInverter cannot be found on our inverter records.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVInverter cannot be found on our inverter records.']);
                                    }
                                } else {
                                    $this->externalAPINotify('Equipment->PVInverter is missing.', $company, null, 0);
                                    return response()->json(['success' => false, 'message' => 'Equipment->PVInverter is missing.']);
                                }

                                if(isset($request['Equipment']['PVInverter_2'])) {
                                    if(!isset($request['Equipment']['PVInverter_2']['Type'])) {
                                        $this->externalAPINotify('Equipment->PVInverter_2->Type is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVInverter_2->Type is missing.']);
                                    } else if(!isset($request['Equipment']['PVInverter_2']['SubType'])) {
                                        $this->externalAPINotify('Equipment->PVInverter_2->SubType is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVInverter_2->SubType is missing.']);
                                    }

                                    $inverter = PVInverter::where('module', $request['Equipment']['PVInverter_2']['Type'])->where('submodule', $request['Equipment']['PVInverter_2']['SubType'])->first();
                                    $customInverter = CustomInverter::where('mfr', $request['Equipment']['PVInverter_2']['Type'])->where('model', $request['Equipment']['PVInverter_2']['SubType'])->where('client_no', $company['id'])->first();
                                    if(!$inverter && !$customInverter) {
                                        $this->externalAPINotify('Equipment->PVInverter_2 cannot be found on our inverter records.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVInverter_2 cannot be found on our inverter records.']);
                                    }
                                }

                                if(isset($request['Equipment']['PVInverter_3'])) {
                                    if(!isset($request['Equipment']['PVInverter_3']['Type'])) {
                                        $this->externalAPINotify('Equipment->PVInverter_3->Type is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVInverter_3->Type is missing.']);
                                    } else if(!isset($request['Equipment']['PVInverter_3']['SubType'])) {
                                        $this->externalAPINotify('Equipment->PVInverter_3->SubType is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVInverter_3->SubType is missing.']);
                                    }

                                    $inverter = PVInverter::where('module', $request['Equipment']['PVInverter_3']['Type'])->where('submodule', $request['Equipment']['PVInverter_3']['SubType'])->first();
                                    $customInverter = CustomInverter::where('mfr', $request['Equipment']['PVInverter_3']['Type'])->where('model', $request['Equipment']['PVInverter_3']['SubType'])->where('client_no', $company['id'])->first();
                                    if(!$inverter && !$customInverter) {
                                        $this->externalAPINotify('Equipment->PVInverter_3 cannot be found on our inverter records.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->PVInverter_3 cannot be found on our inverter records.']);
                                    }
                                }

                                if(isset($request['Equipment']['RailSupportSystem'])) {
                                    if(!isset($request['Equipment']['RailSupportSystem']['Type'])) {
                                        $this->externalAPINotify('Equipment->RailSupportSystem->Type is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->RailSupportSystem->Type is missing.']);
                                    } else if(!isset($request['Equipment']['RailSupportSystem']['SubType'])) {
                                        $this->externalAPINotify('Equipment->RailSupportSystem->SubType is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->RailSupportSystem->SubType is missing.']);
                                    }

                                    $railsupport = RailSupport::where('module', $request['Equipment']['RailSupportSystem']['Type'])->where('submodule', $request['Equipment']['RailSupportSystem']['SubType'])->first();
                                    $customRail = CustomRacking::where('mfr', $request['Equipment']['RailSupportSystem']['Type'])->where('model', $request['Equipment']['RailSupportSystem']['SubType'])->where('client_no', $company['id'])->first();
                                    if(!$railsupport && !$customRail) {
                                        $this->externalAPINotify('Equipment->RailSupportSystem cannot be found on our railsupport records.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->RailSupportSystem cannot be found on our railsupport records.']);
                                    }
                                } else {
                                    $this->externalAPINotify('Equipment->RailSupportSystem is missing.', $company, null, 0);
                                    return response()->json(['success' => false, 'message' => 'Equipment->RailSupportSystem is missing.']);
                                }

                                if(isset($request['Equipment']['Stanchion'])) {
                                    if(!isset($request['Equipment']['Stanchion']['Type'])) {
                                        $this->externalAPINotify('Equipment->Stanchion->Type is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->Stanchion->Type is missing.']);
                                    } else if(!isset($request['Equipment']['Stanchion']['SubType'])) {
                                        $this->externalAPINotify('Equipment->Stanchion->SubType is missing.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->Stanchion->SubType is missing.']);
                                    }

                                    $stanchion = Stanchion::where('module', $request['Equipment']['Stanchion']['Type'])->where('submodule', $request['Equipment']['Stanchion']['SubType'])->first();
                                    $customStanchion = CustomStanchion::where('mfr', $request['Equipment']['Stanchion']['Type'])->where('model', $request['Equipment']['Stanchion']['SubType'])->where('client_no', $company['id'])->first();
                                    if(!$stanchion && !$customStanchion) {
                                        $this->externalAPINotify('Equipment->Stanchion cannot be found on our stanchion records.', $company, null, 0);
                                        return response()->json(['success' => false, 'message' => 'Equipment->Stanchion cannot be found on our stanchion records.']);
                                    }
                                } else {
                                    $this->externalAPINotify('Equipment->Stanchion is missing.', $company, null, 0);
                                    return response()->json(['success' => false, 'message' => 'Equipment->Stanchion is missing.']);
                                }

                                $data['Equipment'] = $request['Equipment'];
                            } else {
                                $this->externalAPINotify('Equipment is missing.', $company, null, 0);
                                return response()->json(['success' => false, 'message' => 'Equipment is missing.']);
                            }

                            if($request['NumberLoadingConditions']) 
                                $data['NumberLoadingConditions'] = $request['NumberLoadingConditions'];
                            else {
                                $this->externalAPINotify('NumberLoadingConditions is missing.', $company, null, 0);
                                return response()->json(['success' => false, 'message' => 'NumberLoadingConditions is missing.']);
                            }
        
                            $data['ProjectInfo']['Number'] = $projectNum;
                            
                            if($request['LoadingCase'] && count($request['LoadingCase']) > 0) {
                                $sample = $data['LoadingCase'][0];
                                $data['LoadingCase'] = array();
                                $i = 1;
                                foreach($request['LoadingCase'] as $caseInput) {
                                    $case = $sample;
                                    if($caseInput['RoofDataInput']) {
                                        $keys = array('A1', 'A2', 'A3', 'A4');
                                        foreach($keys as $key) {
                                            if(!isset($caseInput['RoofDataInput'][$key])) {
                                                $this->externalAPINotify('LoadingCase' . $i . '->' . $key . ' is missing.', $company, null, 0);
                                                return response()->json(['success' => false, 'message' => 'LoadingCase' . $i . '->' . $key . ' is missing.']);
                                            }
                                        }

                                        $case['RoofDataInput']['A1'] = $caseInput['RoofDataInput']['A1'];
                                        $case['RoofDataInput']['A2_feet'] = $caseInput['RoofDataInput']['A2'];
                                        $case['RoofDataInput']['A2'] = $caseInput['RoofDataInput']['A2'];
                                        $case['RoofDataInput']['A3_feet'] = $caseInput['RoofDataInput']['A3'];
                                        $case['RoofDataInput']['A3'] = $caseInput['RoofDataInput']['A3'];
                                        $case['RoofDataInput']['A4_feet'] = $caseInput['RoofDataInput']['A4'];
                                        $case['RoofDataInput']['A4'] = $caseInput['RoofDataInput']['A4'];
                                        if(isset($caseInput['RoofDataInput']['A5']))
                                            $case['RoofDataInput']['A5'] = $caseInput['RoofDataInput']['A5'];
                                    }
                                    array_push($data['LoadingCase'], $case);
                                    $i ++;
                                }
                            }
        
                            if($request['Wind']) $data['Wind'] = $request['Wind'];
                            if($request['WindCheckbox']) $data['WindCheckbox'] = $request['WindCheckbox'];
                            if($request['Snow']) $data['Snow'] = $request['Snow'];
                            if($request['SnowCheckbox']) $data['SnowCheckbox'] = $request['SnowCheckbox'];
                            if($request['IBC']) $data['IBC'] = $request['IBC'];
                            if($request['ASCE']) $data['ASCE'] = $request['ASCE'];
                            if($request['NEC']) $data['NEC'] = $request['NEC'];
                            if($request['WindExposure']) $data['WindExposure'] = $request['WindExposure'];
                            if($request['Units']) $data['Units'] = $request['Units'];
        
                            $current_time = time();
                            $company = Company::where('id', $user->companyid)->first();
                            $companyNumber = $company ? $company['company_number'] : 0;
                            $folderPrefix = "/" . $companyNumber. '. ' . $company['company_name'] . '/';
                            $filename = ($company ? sprintf("%06d", $company['company_number']) : "000000") . "-" . sprintf("%04d", $user->id) . "-" . $current_time . ".json";
        
                            $project = JobRequest::create([
                                'companyName' => $company['company_name'],
                                'companyId' => $user->companyid,
                                'userId' => $user->usernumber,
                                'creator' => $user->usernumber,
                                'clientProjectName' => $data['ProjectInfo']['Name'],
                                'clientProjectNumber' => $data['ProjectInfo']['Number'],
                                'requestFile' => $filename,
                                'planStatus' => 0,
                                'projectState' => 1,
                                'analysisType' => 0,
                                'createdTime' => gmdate("Y-m-d\TH:i:s", $current_time),
                                'submittedTime' => gmdate("Y-m-d\TH:i:s", $current_time),
                                'timesDownloaded' => 0,
                                'timesEmailed' => 0,
                                'timesComputed' => 0,
                                'state' => $data['ProjectInfo']['State']
                            ]);
                            
                            $company->last_accessed = gmdate("Y-m-d", time());
                            $company->save();
                            Storage::disk('input')->put($folderPrefix . $filename, json_encode($data));
        
                            //Backup json file to dropbox
                            $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                            $dropbox = new Dropbox($app);
                            $dropboxFile = new DropboxFile(storage_path('/input/') . $companyNumber. '. ' . $company['company_name'] . '/' . $filename);
                            $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_JSON_INPUT') . $companyNumber. '. ' . $company['company_name'] . '/' . $filename, ['autorename' => TRUE]);
        
                            $this->externalAPINotify("You can now work on {$data['ProjectInfo']['Number']}. {$data['ProjectInfo']['Name']} {$data['ProjectInfo']['State']}.", $company, $user, 1);
                            return response()->json(['success' => true, 'ProjectName' => $data['ProjectInfo']['Name'], "ProjectNumber" => $data['ProjectInfo']['Number']]);
                        } else {
                            $this->externalAPINotify('Sample not found.', $company, null, 0);
                            return response()->json(['success' => false, 'message' => 'Sample not found.']);
                        }
                    } else {
                        $this->externalAPINotify('User Record Not Found.', $company, null, 0);
                        return response()->json(['success' => false, 'message' => 'User Record Not Found.']);
                    }
                } else {
                    $this->externalAPINotify('Missing User Number.', $company, null, 0);
                    return response()->json(['success' => false, 'message' => 'Missing User Number.']);
                }
            } else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        } else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Update Electric JSON
     *
     * @return JSON
     */
    public function updateElectric(Request $request) {
        if(isset($request['CompanyId']) && isset($request['APIKey'])){
            $company = Company::where('id', '=', $request['CompanyId'])->where('api_key', '=', $request['APIKey'])->first();
            if($company) {
                if(isset($request['ProjectNumber'])) {
                    $project = JobRequest::where('companyId', $company['id'])->where('clientProjectNumber', $request['ProjectNumber'])->first();
                    if($project) {
                        $companyNumber = $company ? $company['company_number'] : 0;
                        $folderPrefix = "/" . $companyNumber. '. ' . $project['companyName'] . '/';
                        if( Storage::disk('input')->exists($folderPrefix . $project['requestFile']) ) {
                            $data = json_decode(Storage::disk('input')->get($folderPrefix . $project['requestFile']), true);

                            if(!isset($data['Electrical']))
                                $data['Electrical'] = array('StrTable' => array(), 'ACTable' => array(), 'Main' => array());

                            if(isset($request['StrTable'])) {
                                for($i = 1; $i <= 10; $i ++) {
                                    if(isset($request['StrTable']["R{$i}"])){
                                        $keys = array('InvNo', 'StringNumber', 'ModulesPerString', 'StringsPerMPPT', 'StringLength');
                                        foreach($keys as $key) {
                                            if(!isset($request['StrTable']["R{$i}"][$key])) {
                                                return response()->json(['success' => false, 'message' => 'StrTable->R' . $i . '->' . $key . ' is missing.']);
                                            }
                                        }
                                    }
                                }
                                $data['Electrical']['StrTable'] = $request['StrTable'];
                            }

                            if(isset($request['ACTable'])) {
                                for($i = 1; $i <= 10; $i ++) {
                                    if(isset($request['ACTable']["AC{$i}"])) {
                                        $keys = array('InvNo', 'InvType', 'WireLength', 'MinWireSize', 'Material', 'InsulRating', 'Circuits');
                                        foreach($keys as $key) {
                                            if(!isset($request['ACTable']["AC{$i}"][$key])) {
                                                return response()->json(['success' => false, 'message' => 'ACTable->AC' . $i . '->' . $key . ' is missing.']);
                                            }
                                        }
                                    }
                                }
                                $data['Electrical']['ACTable'] = $request['ACTable'];
                            }

                            if(isset($request['Main'])) {
                                if(isset($request['Main']['InterconnectionType'])) 
                                    $data['Electrical']['Main']['InterconnectionType'] = $request['Main']['InterconnectionType'];
                                if(isset($request['Main']['BusBarRating'])) 
                                    $data['Electrical']['Main']['BusBarRating'] = $request['Main']['BusBarRating'];
                                if(isset($request['Main']['MainBreakerRating'])) 
                                    $data['Electrical']['Main']['MainBreakerRating'] = $request['Main']['MainBreakerRating'];
                                if(isset($request['Main']['PropLoadCenter'])) 
                                    $data['Electrical']['Main']['PropLoadCenter'] = $request['Main']['PropLoadCenter'];
                                if(isset($request['Main']['PropLCBreaker'])) 
                                    $data['Electrical']['Main']['PropLCBreaker'] = $request['Main']['PropLCBreaker'];
                                if(isset($request['Main']['DowngradedBreakerRating'])) 
                                    $data['Electrical']['Main']['DowngradedBreakerRating'] = $request['Main']['DowngradedBreakerRating'];
                                if(isset($request['Main']['PVBreakerRecommended'])) 
                                    $data['Electrical']['Main']['PVBreakerRecommended'] = $request['Main']['PVBreakerRecommended'];
                                if(isset($request['Main']['PVBreakerSelected'])) 
                                    $data['Electrical']['Main']['PVBreakerSelected'] = $request['Main']['PVBreakerSelected'];
                            }

                            Storage::disk('input')->delete($folderPrefix . $project['requestFile']);
                            Storage::disk('input')->put($folderPrefix . $project['requestFile'], json_encode($data));

                            //Backup json file to dropbox
                            $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                            $dropbox = new Dropbox($app);
                            $dropboxFile = new DropboxFile(storage_path('/input/') . $companyNumber. '. ' . $project['companyName'] . '/' . $project['requestFile']);
                            $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_JSON_INPUT') . $companyNumber. '. ' . $project['companyName'] . '/' . $project['requestFile'], ['mode' => 'overwrite']);

                            $this->externalAPINotify("Electric Data Updated on {$data['ProjectInfo']['Number']}. {$data['ProjectInfo']['Name']} {$data['ProjectInfo']['State']}.", $company, null, 1, true);
                            return response()->json(['success' => true]);

                        } else
                            return response()->json(['success' => false, 'message' => 'Cannot find the job file.']);
                    } else
                        return response()->json(['success' => false, 'message' => 'Cannot find the job.']);
                } else
                    return response()->json(['success' => false, 'message' => 'Missing Project Number.']);
            } else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        } else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Upload Job File
     *
     * @return JSON
     */
    public function uploadJobFile(Request $request) {
        if(isset($request['CompanyId']) && isset($request['APIKey'])){
            $company = Company::where('id', '=', $request['CompanyId'])->where('api_key', '=', $request['APIKey'])->first();
            if($company) {
                if(isset($request['ProjectNumber'])) {
                    $project = JobRequest::where('companyId', $company['id'])->where('clientProjectNumber', $request['ProjectNumber'])->first();
                    if($project) {
                        if(!empty($request->file('upl'))) {
                            $companyNumber = $company ? $company['company_number'] : 0;
                            $folderPrefix = "/" . $companyNumber. '. ' . $project['companyName'] . '/';

                            $file = $request->file('upl');

                            $filename = str_replace(array(":",";", "#", "&", "@", "/", "'"), array(""), $file->getClientOriginalName());

                            $filepath = $folderPrefix . $project['clientProjectNumber'] . '. ' . $project['clientProjectName'] . ' ' . $project['state'];
                            $file->move(storage_path('upload') . $filepath, $filename);
                            $localpath = storage_path('upload') . $filepath . '/' . $filename;

                            if($project->planStatus == 0)
                                $project->planStatus = 1;
                            $project->save();

                            $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                            $dropbox = new Dropbox($app);
                            $dropboxFile = new DropboxFile($localpath);
                            $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath . '/' . $file->getClientOriginalName(), ['autorename' => TRUE]);

                            return response()->json(['success' => true, 'message' => 'Uploaded Successfully', 'filename' => $filename]);
                        } else 
                            return response()->json(['success' => false, 'message' => 'Empty File.']);
                    } else
                        return response()->json(['success' => false, 'message' => 'Cannot find the job.']);
                } else
                    return response()->json(['success' => false, 'message' => 'Missing Project Number.']);
            } else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        } else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Return Job Status
     *
     * @return JSON
     */
    public function getJobStatus(Request $request) {
        if(isset($request['CompanyId']) && isset($request['APIKey'])){
            $company = Company::where('id', '=', $request['CompanyId'])->where('api_key', '=', $request['APIKey'])->first();
            if($company) {
                if(isset($request['ProjectNumber'])) {
                    $project = JobRequest::where('companyId', $company['id'])->where('clientProjectNumber', $request['ProjectNumber'])->first();
                    if($project) {
                        return response()->json(['success' => true, 'status' => $project->planStatus]);
                    } else
                        return response()->json(['success' => false, 'message' => 'Cannot find the job.']);
                } else
                    return response()->json(['success' => false, 'message' => 'Missing Project Number.']);
            } else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        } else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Return Job Files as a ZIP
     *
     * @return JSON OR FILE
     */
    public function downloadJobFiles(Request $request) {
        if(isset($request['CompanyId']) && isset($request['APIKey'])){
            $company = Company::where('id', '=', $request['CompanyId'])->where('api_key', '=', $request['APIKey'])->first();
            if($company) {
                if(isset($request['ProjectNumber'])) {
                    $project = JobRequest::where('companyId', $company['id'])->where('clientProjectNumber', $request['ProjectNumber'])->first();
                    if($project) {
                        $zip = new ZipArchive();
                        $filename = $project['clientProjectNumber'] . '. ' . $project['clientProjectName'] . ' ' . $project['state'] . ".zip";

                        if(file_exists(storage_path('download') . '/' . $filename))
                            unlink(storage_path('download') . '/' . $filename);
                        
                        $zip->open(storage_path('download') . '/' . $filename, ZipArchive::CREATE);

                        $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                        $dropbox = new Dropbox($app);
                        try{
                            $this->iterateDropboxToZip($dropbox, $zip, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_OUT') . $filepath . '/', '');
                        } catch (DropboxClientException $e) { 
                            return response()->json(['success' => false, 'message' => 'Error while zipping.']);
                        }

                        return response()->download(storage_path('download') . '/' . $filename, null, ['Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0']);
                    } else
                        return response()->json(['success' => false, 'message' => 'Cannot find the job.']);
                } else
                    return response()->json(['success' => false, 'message' => 'Missing Project Number.']);
            } else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        } else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Iterate all the subdirectories from the dropbox.
     *
     * @return Object
     */
    private function iterateDropboxToZip(Dropbox $dropbox, ZipArchive $zip, String $dropboxPath, String $folderPath) {
        $listFolderContents = $dropbox->listFolder($dropboxPath);
        $files = $listFolderContents->getItems()->all();
        foreach($files as $file){
            if($file->getDataProperty('.tag') === 'file') {
                $file = $dropbox->download($dropboxPath . $file->getName());
                $path = $folderPath . '/' . $file->getName();
                $zip->addFromString($path, $file->getContents());
            }
            else
                $this->iterateDropboxToZip($dropbox, $zip, $dropboxPath . $file->getName() . '/', $folderPath . '/' . $file->getName());
        }
    }
}
