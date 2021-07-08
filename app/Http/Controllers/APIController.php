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
use App\JobChat;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Ifsnop\Mysqldump as IMysqldump;

use DateTime;
use DateTimeZone;
use Mail;

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
}
