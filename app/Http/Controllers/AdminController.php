<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Company;
use App\JobRequest;
use App\BackupSetting;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Ifsnop\Mysqldump as IMysqldump;

use DateTime;
use DateTimeZone;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect('home');
    }

    /**
     * Show the backup /restore JSON inputs interface.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function backupInput()
    {
        if(Auth::user()->userrole == 2){
            $companyList = Company::orderBy('company_name', 'asc')->get();
            return view('admin.backupinput.backup')->with('companyList', $companyList);
        }
        else
            return redirect('home');
    }

    /**
     * Return the filename and exist status on dropbox / godaddy
     *
     * @return JSON
     */
    public function getProjectFiles(Request $request)
    {
        if(Auth::user()->userrole == 2){
            $query = new JobRequest;

            if(isset($request['company']) && $request['company'] != '' && $request['company'] != '-1')
                $query = $query->where('companyId', $request['company']);
            if(isset($request['dateFrom']) && $request['dateFrom'] != ''){
                $date = new DateTime($request['dateFrom'], new DateTimeZone('EST'));
                $date->setTimezone(new DateTimeZone('UTC'));
                $query = $query->where('createdTime', '>=', $date->format("Y-m-d H:i:s"));
            }
            if(isset($request['dateTo']) && $request['dateTo'] != '')
            {
                $dateTo = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($request['dateTo'])));
                $date = new DateTime($dateTo, new DateTimeZone('EST'));
                $date->setTimezone(new DateTimeZone('UTC'));
                $query = $query->where('createdTime', '<=', $date->format("Y-m-d H:i:s"));
            }
            if(isset($request['idFrom']) && $request['idFrom'] != '')
                $query = $query->whereRaw('CAST(clientProjectNumber AS SIGNED) >= ' . $request['idFrom']);
                
            if(isset($request['idTo']) && $request['idTo'] != '')
                $query = $query->whereRaw('CAST(clientProjectNumber AS SIGNED) <= ' . $request['idTo']);
            
            $data = $query->get();
            if(count($data) > 0){
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                $files = array();
                foreach($data as $job){
                    $name = explode("-", $job['requestFile']);
                    $filepath = $name[0] . '. ' . $job['companyName'] . '/' . $job['requestFile'];
                    $file = array('file' => $filepath);
                    // try{
                    //     $metadata = $dropbox->getMetadata(env('DROPBOX_JSON_INPUT') . $filepath);
                    //     if($metadata)
                    //         $file['dropbox'] = true;
                    //     else
                    //         $file['dropbox'] = false;
                    // } catch (DropboxClientException $e) { 
                    //     $file['dropbox'] = false;
                    // }
                    if(Storage::disk('input')->exists('/' . $filepath))
                        $file['local'] = true;
                    else
                        $file['local'] = false;
                    $files[] = $file;
                }
                return response()->json(['success' => true, 'data' => $files]);
            } else
                return response()->json(['success' => true, 'data' => array()]);
        }
        else
            return response()->json(['success' => false, 'message' => 'You do not have any role to pass this API.']);
    }

    /**
     * Backup the Input JSON to Dropbox
     *
     * @return JSON
     */
    public function backupJSON(Request $request){
        if(isset($request['file'])){
            if(Storage::disk('input')->exists('/' . $request['file'])){
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                $dropboxFile = new DropboxFile(storage_path('/input/') . $request['file']);
                $dropbox->upload($dropboxFile, env('DROPBOX_JSON_INPUT') . $request['file'], ['mode' => 'overwrite']);
                return response()->json(['success' => true]);
            } else
                return response()->json(['success' => false, 'message' => 'File does not exist.']);
        } else
            return response()->json(['success' => false, 'message' => 'Empty File Name']);
    }

    /**
     * Restore the Input JSON to Server
     *
     * @return JSON
     */
    public function restoreJSON(Request $request){
        if(isset($request['file'])){
            $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
            $dropbox = new Dropbox($app);
            try {
                $meta = $dropbox->getMetaData(env('DROPBOX_JSON_INPUT') . $request['file']);
                if($meta){
                    if(Storage::disk('input')->exists('/' . $request['file']))
                        Storage::disk('input')->delete('/' . $request['file']);
                    $dropbox->download(env('DROPBOX_JSON_INPUT') . $request['file'], storage_path('/input/') . $request['file']);
                    return response()->json(['success' => true]);
                } else 
                    return response()->json(['success' => false, 'message' => 'File does not exist.']);
            } catch (DropboxClientException $e) {
                return response()->json(['success' => false, 'message' => 'File does not exist.']);
            }
        } else
            return response()->json(['success' => false, 'message' => 'Empty File Name']);
    }

    /**
     * Show the backup /restore DB interface.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function backupDB()
    {
        if(Auth::user()->userrole == 2){
            $settingData = BackupSetting::first();
            if(!$settingData)
                $setting = array("-1");
            else
                $setting = explode(",", $settingData['backup_days']);
            return view('admin.backupdb.backup')->with('setting', $setting);
        }
        else
            return redirect('home');
    }

    /**
     * Update BackupSetting.
     *
     * @return JSON
     */
    public function updateDBSetting(Request $request){
        if(Auth::user()->userrole == 2){
            if(isset($request['setting'])){
                $setting = BackupSetting::first();
                if(!$setting){
                    $setting = BackupSetting::create([
                        'backup_days' => $request['setting']
                    ]);
                    return response()->json(['success' => true]);
                } else {
                    $setting['backup_days'] = $request['setting'];
                    $setting->save();
                    return response()->json(['success' => true]);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Empty File Name']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'You do not have any role to pass this API.']);
        }
    }

    /**
     * Backup DB and upload to dropbox manually from admin panel
     *
     * @return JSON
     */
    public function manualDBBackup(Request $request){
        if(Auth::user()->userrole == 2){
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
        } else {
            return response()->json(['success' => false, 'message' => 'You do not have any role to pass this API.']);
        }
    }
}
