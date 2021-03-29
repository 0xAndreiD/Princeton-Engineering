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
use App\LoginGuard;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Ifsnop\Mysqldump as IMysqldump;

use DateTime;
use DateTimeZone;
use DB;

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
        $this->middleware('twofactor');
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
            
            try{
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                $listFolderContents = $dropbox->listFolder(env('DROPBOX_DB_BACKUP'));
                $files = $listFolderContents->getItems()->all();
                $list = array();
                foreach($files as $file)
                    $list[] = array('filename' => $file->getName(), 'modified' => date('Y-m-d H:i:s', strtotime('-5 hour',strtotime($file->getClientModified()))));
            }catch(DropboxClientException $e){
                $list = array();
            }
            
            return view('admin.backupdb.backup')->with('setting', $setting)->with('list', $list);
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
                return response()->json(['success' => true, 'filename'=> $filename, 'modified' => date('Y-m-d H:i:s', strtotime('-5 hour',strtotime($dropfile->getClientModified())))]);
    
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'You do not have any role to pass this API.']);
        }
    }

    /**
     * Delete Backup file on both server and dropbox
     *
     * @return JSON
     */
    public function delBackup(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['filename'])){
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                try {
                    $dropbox->delete(env('DROPBOX_DB_BACKUP') . $request['filename']);
                    if(Storage::disk('db')->exists('/' . $request['filename']))
                        Storage::disk('db')->delete('/' . $request['filename']);
                    return response()->json(['success' => true]);
                }
                catch (DropboxClientException $e) {
                    return response()->json(['success' => false, 'message' => 'Cannot find specified file.']);
                }
            } else 
                return response()->json(['success' => false, 'message' => 'Missing filename parameter.']);
        } else {
            return response()->json(['success' => false, 'message' => 'You do not have any role to pass this API.']);
        }
    }

    /**
     * Restore Backup file to database from dropbox
     *
     * @return JSON
     */
    public function restoreBackup(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['filename'])){
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                try {
                    $file = $dropbox->download(env('DROPBOX_DB_BACKUP') . $request['filename']);
                    $content = $file->getContents();
                }
                catch (DropboxClientException $e) {
                    return response()->json(['success' => false, 'message' => 'Cannot find specified file.']);
                }
                //$content = file_get_contents(storage_path('/db/') . $request['filename']);

                $lines = explode("\n", $content);
                $templine = '';

                foreach($lines as $line){
                    // Skip it if it's a comment
                    if (substr($line, 0, 2) == '--' || $line == '')
                        continue;

                    $templine .= $line;
                    if (substr(trim($line), -1, 1) == ';')
                    {
                        // Perform the query
                        DB::connection()->getpdo()->exec($templine);
                        // Reset temp variable to empty
                        $templine = '';
                    }
                }
                return response()->json(['success' => true]);
            } else 
                return response()->json(['success' => false, 'message' => 'Missing filename parameter.']);
        } else {
            return response()->json(['success' => false, 'message' => 'You do not have any role to pass this API.']);
        }
    }

    /**
     * Show the list of guard list
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function guardlist()
    {
        if(Auth::user()->userrole == 2)
            return view('admin.loginguard.guardlist');
        else
            return redirect('home');
    }

    /**
     * Return the list of login guards.
     *
     * @return JSON
     */
    public function getGuardList(Request $request){
        $columns = array( 
            0 =>'login_guard.id', 
            1 =>'company_info.company_name',
            2 =>'users.username',
            3 =>'ipAddress',
            4 =>'identity',
            5 =>'login_guard.created_at',
            6 =>'allowed'
        );
        $handler = new LoginGuard;

        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        $handler = $handler->leftjoin('users', "users.id", "=", "login_guard.userId");
        $handler = $handler->leftjoin('company_info', "company_info.id", "=", "users.companyid");
        // if(Auth::user()->userrole == 2 && !empty($request->input("columns.1.search.value")))
        //     $handler = $handler->where('mfr', 'LIKE', "%{$request->input("columns.1.search.value")}%");

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $totalFiltered = $handler->count();
            $data = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array(
                        'login_guard.id as id', 'company_info.company_name as company', 'users.username as username', 'ipAddress as ip', 'identity as device', 'login_guard.created_at as created_at', 'allowed', 'blocked'
                    )
                );
        }
        else {
            $search = $request->input('search.value'); 
            $data =  $handler->where('login_guard.id', 'LIKE',"%{$search}%")
                        ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('users.username', 'LIKE',"%{$search}%")
                        ->orWhere('ipAddress', 'LIKE',"%{$search}%")
                        ->orWhere('identity', 'LIKE',"%{$search}%")
                        ->orWhere('login_guard.created_at', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array(
                                'login_guard.id as id', 'company_info.company_name as company', 'users.username as username', 'ipAddress as ip', 'identity as device', 'login_guard.created_at as created_at', 'allowed', 'blocked'
                            )
                        );

            $totalFiltered = $handler->where('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->count();
        }

        if(!empty($data))
        {
            foreach ($data as $item)
            {
                if($item['allowed'] == 0){
                    $item['state'] = "<span class='badge badge-warning'>First Login</span>";
                } else if($item['allowed'] == 1){
                    $item['state'] = "<span class='badge badge-primary'>Code Verified</span>";
                } else if($item['allowed'] == 2){
                    $item['state'] = "<span class='badge badge-success'>Added by Admin</span>";
                }
                if($item['blocked'] == 1)
                    $item['state'] = "<span class='badge badge-danger'>Blocked</span>";
                $item['actions'] = "
                <div class='text-center'>" . 
                    ($item['blocked'] == 0 ? "<button type='button' class='btn btn-danger mr-1' onclick='toggleBlock({$item['id']}, 1)'>Block</button>" : "<button type='button' class='btn btn-primary' onclick='toggleBlock({$item['id']}, 0)'>Allow</button>")
                    . "<button type='button' class='btn btn-danger' onclick='delGuard({$item['id']})'><i class='fa fa-trash'></i></button>"
                    . "</div>";
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
        echo json_encode($json_data);
    }

    /**
     * Block/Allow guard item
     *
     * @return JSON
     */
    public function toggleBlock(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['id'])){
               $guard = LoginGuard::where('id', $request['id'])->first();
               if($guard){
                $guard->blocked = !$guard->blocked;
                $guard->save();
                return response()->json(['success' => true]);
               } else 
                return response()->json(['success' => false, 'message' => 'Guard not found.']);
            } else 
                return response()->json(['success' => false, 'message' => 'Missing id parameter.']);
        } else {
            return response()->json(['success' => false, 'message' => 'You do not have any role to pass this API.']);
        }
    }

    /**
     * Delete guard item
     *
     * @return JSON
     */
    public function deleteGuard(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['id'])){
               $guard = LoginGuard::where('id', $request['id'])->first();
               if($guard){
                $guard->delete();
                return response()->json(['success' => true]);
               } else 
                return response()->json(['success' => false, 'message' => 'Guard not found.']);
            } else 
                return response()->json(['success' => false, 'message' => 'Missing id parameter.']);
        } else {
            return response()->json(['success' => false, 'message' => 'You do not have any role to pass this API.']);
        }
    }
}
