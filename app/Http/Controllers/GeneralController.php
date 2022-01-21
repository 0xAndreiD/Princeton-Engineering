<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Company;
use App\JobProjectStatus;
use App\JobPlanStatus;
use App\PVModuleCEC;
use App\PVModule;
use App\PVInverter;
use App\Stanchion;
use App\RailSupport;
use App\JobRequest;
use App\JobPermit;
use App\DataCheck;
use App\UserSetting;
use App\ASCERoofTypes;
use App\ASCEYear;
use App\CustomModule;
use App\CustomInverter;
use App\CustomStanchion;
use App\CustomRacking;
use App\StandardFavorite;
use App\JobChat;
use App\PermitFiles;
use App\PermitInfo;
use App\PermitFields;
use App\StructuralNotes;
use App\TownNameLocations;
use App\BillingInfo;
use App\BillingHistory;
use App\SystemMsgs;
use App\BlgMaterials;
use App\SubClients;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;

use DateTime;
use DateTimeZone;
use ZipArchive;
use DB;
use Mail;
use Response;
use mikehaertl\pdftk\Pdf;

class GeneralController extends Controller
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
    public function index(Request $request)
    {
        $notify = 0;
        if($request['notify']){
            $type = base64_decode($request['notify']);
            if($type == 'guardset')
                $notify = 1;
        }

        if( Auth::user()->userrole == 2 )
            return view('admin.home')->with('notify', $notify);
        else if( Auth::user()->userrole == 1 || Auth::user()->userrole == 3)
            return view('clientadmin.home')->with('notify', $notify);
        else if( Auth::user()->userrole == 4 )
            return view('reviewer.home')->with('notify', $notify);
        else if( Auth::user()->userrole == 0 )
            return view('user.home')->with('notify', $notify);
    }

    /**
     * Show the statistics page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function statistics(Request $request)
    {
        if(!empty($request['userId'])){
            $user = User::where('id', $request['userId'])->first();
            if($user){
                if( Auth::user()->userrole == 2 || Auth::user()->userrole == 3)
                    return view('clientadmin.statistics.user')->with('user', $user);
                else if(Auth::user()->userrole == 1 && $user->companyid == Auth::user()->companyid)
                    return view('clientadmin.statistics.user')->with('user', $user);
                else
                    return redirect('home');
            }else
                return redirect('home');
        } else {
            $companyList = Company::orderBy('company_name', 'asc')->get();

            if( Auth::user()->userrole == 2 )
                return view('admin.statistics.users')->with('companyList', $companyList);
            else if( Auth::user()->userrole == 1 || Auth::user()->userrole == 3)
                return view('clientadmin.statistics.users')->with('companyList', $companyList);
            else
                return redirect('home');
        }
    }

    /**
     * Calcs the company summary data.
     *
     * @return JSON
     */
    public function getCompanySummary(Request $request){
        $summaryData = array();
        if( Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 ){
            if(!empty($request['companyId'])){
                $summaryData['opened'] = JobRequest::where('companyId', $request['companyId'])->where('projectState', '!=', 9)->count();
                $summaryData['completed'] = JobRequest::where('companyId', $request['companyId'])->where('projectState', 9)->count();
                $summaryData['chatstotal'] = JobChat::leftjoin('users', "users.id", "=", "job_chat.userId")->where('users.companyId', $request['companyId'])->count();
                $maxchat = DB::select(DB::raw("SELECT job_request.companyId as companyId, job_request.clientProjectName as projectName, job_request.clientProjectNumber as projectNumber, COUNT(*) AS count FROM `job_chat` LEFT JOIN `job_request` ON `job_chat`.`jobId` = `job_request`.`id` WHERE job_request.companyId = ".$request['companyId']." GROUP BY `job_chat`.`jobId` ORDER BY count DESC LIMIT 1"));
                if($maxchat && $maxchat[0]){
                    $maxchatjob = $maxchat[0];
                    $summaryData['maxchat'] = json_decode(json_encode($maxchatjob),true);
                    $maxchatcompany = Company::where('id', $maxchatjob->companyId)->first();
                    $summaryData['maxchat']['companyName'] = $maxchatcompany ? $maxchatcompany->company_name : "";
                }
            } else {
                $summaryData['opened'] = JobRequest::where('projectState', '!=', 9)->count();
                $summaryData['completed'] = JobRequest::where('projectState', 9)->count();
                $summaryData['chatstotal'] = JobChat::count();
                $maxchat = DB::select("SELECT job_request.companyId as companyId, job_request.clientProjectName as projectName, job_request.clientProjectNumber as projectNumber, COUNT(*) AS count FROM `job_chat` LEFT JOIN `job_request` ON `job_chat`.`jobId` = `job_request`.`id` GROUP BY `job_chat`.`jobId` ORDER BY count DESC LIMIT 1");
                if($maxchat && $maxchat[0]){
                    $maxchatjob = $maxchat[0];
                    $summaryData['maxchat'] = json_decode(json_encode($maxchatjob),true);
                    $maxchatcompany = Company::where('id', $maxchatjob->companyId)->first();
                    $summaryData['maxchat']['companyName'] = $maxchatcompany ? $maxchatcompany->company_name : "";
                }
            }
        } else if( Auth::user()->userrole == 1 ){
            $summaryData['opened'] = JobRequest::where('companyId', Auth::user()->companyid)->where('projectState', '!=', 9)->count();
            $summaryData['completed'] = JobRequest::where('companyId', Auth::user()->companyid)->where('projectState', 9)->count();
            $summaryData['chatstotal'] = JobChat::leftjoin('users', "users.id", "=", "job_chat.userId")->where('users.companyId', Auth::user()->companyid)->count();
            $maxchat = DB::select(DB::raw("SELECT job_request.companyId as companyId, job_request.clientProjectName as projectName, job_request.clientProjectNumber as projectNumber, COUNT(*) AS count FROM `job_chat` LEFT JOIN `job_request` ON `job_chat`.`jobId` = `job_request`.`id` WHERE job_request.companyId = ".Auth::user()->companyid." GROUP BY `job_chat`.`jobId` ORDER BY count DESC LIMIT 1"));
            if($maxchat && $maxchat[0]){
                $maxchatjob = $maxchat[0];
                $summaryData['maxchat'] = json_decode(json_encode($maxchatjob),true);
                $maxchatcompany = Company::where('id', $maxchatjob->companyId)->first();
                $summaryData['maxchat']['companyName'] = $maxchatcompany ? $maxchatcompany->company_name : "";
            }
        }
        return response()->json($summaryData);
    }

    /**
     * Return Individual Users' metrics data
     *
     * @return JSON
     */
    public function getUserMetrics(Request $request){
        if(Auth::user()->userrole == 1){
            $columns = array( 
                0 =>'id', 
                1 =>'username',
                2 =>'opened',
                3 =>'completed',
                4 => 'totalchats',
                5 => 'avgchats'
            );
            $handler = new User;
            $handler = $handler->where('companyid', Auth::user()->companyid);
        } else {
            $columns = array( 
                0 =>'id', 
                1 =>'companyname',
                2 =>'username',
                3 =>'opened',
                4 =>'completed',
                5 => 'totalchats',
                6 => 'avgchats'
            );
            $handler = new User;
        }            
        
        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(!empty($request->input("columns.1.search.value")))
            $handler = $handler->where('companyid', '=', $request->input("columns.1.search.value"));

        if(empty($request->input('search.value')))
        {            
            $totalFiltered = $handler->count();
            $users = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->select(DB::raw('users.id as id, (SELECT company_name from company_info WHERE company_info.id = users.companyid) as companyname, users.username as username, users.companyId as cur_companyId, users.usernumber as cur_usernum, ( SELECT COUNT(*) FROM job_request WHERE job_request.companyId = cur_companyId AND job_request.creator = cur_usernum AND job_request.projectState != 9) as opened, ( SELECT COUNT(*) FROM job_request WHERE job_request.companyId = cur_companyId AND job_request.creator = cur_usernum AND job_request.projectState = 9) as completed, ( SELECT COUNT(*) FROM job_chat WHERE job_chat.userId = users.id) as totalchats, ( SELECT COUNT(*) FROM job_chat WHERE job_chat.userId = users.id) / ( SELECT COUNT(distinct job_chat.jobId) FROM job_chat WHERE job_chat.userId = users.id ) as avgchats'))
                ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $users =  $handler->where('id', 'LIKE',"%{$search}%")
                        ->orWhere('username', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->select(DB::raw('users.id as id, (SELECT company_name from company_info WHERE company_info.id = users.companyid) as companyname, users.username as username, users.companyId as cur_companyId, users.usernumber as cur_usernum, ( SELECT COUNT(*) FROM job_request WHERE job_request.companyId = cur_companyId AND job_request.creator = cur_usernum AND job_request.projectState != 9) as opened, ( SELECT COUNT(*) FROM job_request WHERE job_request.companyId = cur_companyId AND job_request.creator = cur_usernum AND job_request.projectState = 9) as completed, ( SELECT COUNT(*) FROM job_chat WHERE job_chat.userId = users.id) as totalchats, ( SELECT COUNT(*) FROM job_chat WHERE job_chat.userId = users.id) / ( SELECT COUNT(distinct job_chat.jobId) FROM job_chat WHERE job_chat.userId = users.id ) as avgchats'))
                        ->get();

            $totalFiltered = $handler->where('id', 'LIKE',"%{$search}%")
                        ->orWhere('username', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $user['actions'] = "
                <div class='text-center'>
                    <a class='btn btn-warning' href='".route('statistics')."?userId=".$user['id']."'>
                        " . "<i class='fa fa-eye'></i>" . 
                    "</a>"
                . "</div>";
                $user['avgchats'] = number_format($user['avgchats'], 1);
                $data[] = $user;
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
     * Return individual user summary info.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUserSummary(Request $request){
        if(!empty($request['userId'])){
            $user = User::where('id', $request['userId'])->first();
            if($user){
                $opened = JobRequest::where('companyId', $user->companyid)->where('creator', $user->usernumber)->where('projectState', '!=', '9')->count();
                $completed = JobRequest::where('companyId', $user->companyid)->where('creator', $user->usernumber)->where('projectState', '=', '9')->count();
                $totalchats = JobChat::where('userId', $user->id)->count();
                $chattedjobs = count(JobChat::where('userId', $user->id)->groupBy('jobId')->get());
                if($chattedjobs == 0)
                    $avgchats = 0;
                else
                    $avgchats = number_format($totalchats / $chattedjobs, 1);
                return response()->json(["success" => true, "info" => ["opened" => $opened, "completed" => $completed, "totalchats" => $totalchats, "avgchats" => $avgchats]]);
            } else 
                return response()->json(["success" => false, "message" => "Cannot find the user."]);
        } else
            return response()->json(["success" => false, "message" => "Missing Parameter."]);
    }

    /**
     * Return individual user projects info.
     *
     * @return JSON
     */
    public function getUserProjects(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'projectNumber',
            2 =>'projectName',
            3 =>'state',
            4 =>'createdTime',
            5 => 'submittedTime',
            6 => 'projectState',
            7 => 'chats'
        );

        $user = User::where('id', $request['userId'])->first();
        if($user){
            $handler = JobRequest::leftjoin('job_pstatus', "job_pstatus.id", "=", "job_request.projectState")->where('job_request.companyid', $user->companyid)->where('job_request.creator', $user->usernumber);
        
            $totalData = $handler->count();
            $totalFiltered = $totalData; 

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if(isset($request["columns.3.search.value"]))
                $handler = $handler->where('job_request.state', 'LIKE', "%{$request->input('columns.3.search.value')}%");

            if(empty($request->input('search.value')))
            {            
                $totalFiltered = $handler->count();
                $jobs = $handler->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->select(DB::raw('job_request.id as id, job_request.clientProjectNumber as projectNumber, job_request.clientProjectName as projectName, job_request.state as state, job_request.createdTime as createdTime, job_request.submittedTime as submittedTime, job_request.projectState as projectState, job_pstatus.color as statecolor, ( SELECT COUNT(*) FROM job_chat WHERE job_chat.jobId = job_request.id ) as chats'))
                    ->get(array());
            }
            else {
                $search = $request->input('search.value'); 
                $jobs =  $handler->where('id', 'LIKE',"%{$search}%")
                            ->orWhere('username', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->select(DB::raw('job_request.id as id, job_request.clientProjectNumber as projectNumber, job_request.clientProjectName as projectName, job_request.state as state, job_request.createdTime as createdTime, job_request.submittedTime as submittedTime, job_request.projectState as projectState, job_pstatus.color as statecolor, ( SELECT COUNT(*) FROM job_chat WHERE job_chat.jobId = job_request.id ) as chats'))
                            ->get();

                $totalFiltered = $handler->where('id', 'LIKE',"%{$search}%")
                            ->orWhere('username', 'LIKE',"%{$search}%")
                            ->count();
            }

            $data = array();

            if(!empty($jobs))
            {
                foreach ($jobs as $job)
                {
                    $globalStates = JobProjectStatus::orderBy('id', 'asc')->get();
                    $statenote = isset($globalStates[intval($job->projectstate)]) ? $globalStates[intval($job->projectstate)]->notes : 'Unknown State';
                    if(!isset($globalStates[intval($job->projectstate)])) $job->statecolor = '#ff0000';
                    $job['projectStatus'] = "<span class='badge' style='white-space: pre-wrap; color: black; background-color: {$job->statecolor};'> {$globalStates[intval($job->projectState)]->notes} </span>";
                    $data[] = $job;
                }
            }
            $json_data = array(
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data   
                );
            echo json_encode($json_data);
        } else {
            return response()->json(["data" => [], "draw" => 1, "recordsFiltered" => 0, "recordsTotal" => 0]);
        }
    }

    /**
     * Show the rf multiple input form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function rsinput(Request $request)
    {
        $company = Company::where('id', Auth::user()->companyid)->first();
        $insulationMaterials = BlgMaterials::where('location', 'Insulation')->get(array('id', 'material'));
        $ceilingMaterials = BlgMaterials::where('location', 'Interior')->get(array('id', 'material'));
        $deckMaterials = BlgMaterials::where('location', 'Decking')->get(array('id', 'material'));
        $surfaceMaterials = BlgMaterials::where('location', 'Roof Surface')->get(array('id', 'material'));
        if( $company )
        {
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if ($project){
                $companymembers = User::where('companyid', $project['companyId'])->get();
            } else {
                $companymembers = "";
            }
            return view('rsinput.body')
                    ->with('companyName', $company['company_name'])
                    ->with('companyNumber', $company['company_number'])
                    ->with('companyMembers', $companymembers)
                    ->with('projectState', $project ? $project->projectState : 0)
                    ->with('planCheck', $project ? $project->planCheck : 0)
                    ->with('asBuilt', $project ? $project->asBuilt : 0)
                    ->with('PIL_status', $project ? $project->PIL_status : 0)
                    ->with('projectId', $request['projectId'] ? $request['projectId'] : -1)
                    ->with('userId', $project ? $project['userId'] : 0)
                    ->with('offset', $company['offset'])
                    ->with('insulationMaterials', $insulationMaterials)
                    ->with('ceilingMaterials', $ceilingMaterials)
                    ->with('deckMaterials', $deckMaterials)
                    ->with('surfaceMaterials', $surfaceMaterials)
                    ->with('date_report', $project ? $project->date_report : NULL);
        }
        else
        {
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            $companymembers = User::where('companyid', $project['companyId'])->get();
            return view('rsinput.body')
                    ->with('companyName', "")
                    ->with('companyNumber', "")
                    ->with('companyMembers', $companymembers)
                    ->with('projectState', $project ? $project->projectState : 0)
                    ->with('planCheck', $project ? $project->planCheck : 0)
                    ->with('asBuilt', $project ? $project->asBuilt : 0)
                    ->with('PIL_status', $project ? $project->PIL_status : 0)
                    ->with('projectId', $request['projectId'] ? $request['projectId'] : -1)
                    ->with('userId', $project ? $project['userId'] : 0)
                    ->with('offset', 0.5)
                    ->with('insulationMaterials', $insulationMaterials)
                    ->with('ceilingMaterials', $ceilingMaterials)
                    ->with('deckMaterials', $deckMaterials)
                    ->with('surfaceMaterials', $surfaceMaterials)
                    ->with('date_report', $project ? $project->date_report : NULL);
        }
    }

    /**
     * Show the rf multiple input form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function reference(Request $request){
        return view('reference.body');
    }

    /**
     * Get the same company member data
     *
     * @return JSON
     */
    public function getUserData(Request $request){
        if($request['userId'])
        {
            $user = User::where('companyid', Auth::user()->companyid)->where('id', $request['userId'])->first();
            if( $user )
                return response()->json($user);
            else
                return response()->json([]);
        }
        else
            return response()->json([]);
    }

    /**
     * Save Input Datas as files
     *
     * @return JSON
     */
    public function submitInput(Request $request){
        // Check if current user's company has outdated unpaid bill
        if($this->checkOutdatedBill())
            return response()->json(["message" => "Your company has overdue unpaid invoices. Please pay these outstanding invoices in order to continue using iRoof.", "status" => false]);

        // Process to save the job data
        if($request['data'] && $request['data']['projectId'] && $request['data']['projectId'] > 0){
            $project = JobRequest::where('id', '=', $request['data']['projectId'])->first();
            if($project){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $project->companyId == Auth::user()->companyid)
                {
                    $company = Company::where('id', $project->companyId)->first();
                    $companyNumber = $company ? $company['company_number'] : 0;
                    $folderPrefix = "/" . $companyNumber. '. ' . $project['companyName'] . '/';
                    
                    $user = '';

                    if (isset($request['data']['option-user-id'])) {
                        $user = User::where('companyid', $project['companyId'])->where('usernumber', $request['data']['option-user-id'])->first();
                    } else {
                        $user = User::where('companyid', $project['companyId'])->where('usernumber', $project['userId'])->first();
                    }
                    
                    $data = $this->inputToJson($request['data'], $request['caseCount'], $user);   

                    $projectState = 0;
                    if($request['status'] == 'Saved')
                        $projectState = 1;
                    else if($request['status'] == 'Data Check'){
                        $projectState = 2;
                        $project->planCheck = 0;
                        $project->asBuilt = 0;
                        $project->PIL_status = 0;
                    }
                    else if($request['status'] == 'Submitted'){
                        $projectState = 4;
                        $project->planCheck = 0;
                        $project->asBuilt = 0;
                        $project->PIL_status = 0;
                    }
                    $project->projectState = $projectState;

                    // We need to rename dropbox project directories on IN, OUT, 
                    if($project->clientProjectName != $request['data']['txt-project-name'] || $project->clientProjectNumber != $request['data']['txt-project-number'] || $project->state != $request['data']['option-state']){
                        $filepath = $folderPrefix . $project['clientProjectNumber'] . '. ' . $project['clientProjectName'] . ' ' . $project['state'];
                        $newpath = $folderPrefix . $request['data']['txt-project-number'] . '. ' . $request['data']['txt-project-name'] . ' ' . $request['data']['option-state'];

                        $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                        $dropbox = new Dropbox($app);

                        try{
                            $dropbox->move(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $newpath);
                        } catch(DropboxClientException $e) {}

                        try{
                            $dropbox->move(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_OUT') . $filepath, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_OUT') . $newpath);
                        } catch(DropboxClientException $e) {}

                        try{
                            $dropbox->move(env('DROPBOX_PROJECTS_PATH') . '/eSealed' . $filepath, env('DROPBOX_PROJECTS_PATH') . '/eSealed' . $newpath);
                        } catch(DropboxClientException $e) {}
                        
                        try{
                            $dropbox->move(env('DROPBOX_PROJECTS_PATH') . '/IN_copy' . $filepath, env('DROPBOX_PROJECTS_PATH') . '/IN_copy' . $newpath);
                        } catch(DropboxClientException $e) {}
                    }

                    $project->clientProjectName = $request['data']['txt-project-name'];
                    $project->clientProjectNumber = $request['data']['txt-project-number'];
                    $project->submittedTime = gmdate("Y-m-d\TH:i:s", time());
                    $project->state = $request['data']['option-state'];
                    $project->analysisType = 0;

                    if (isset($request['data']['option-user-id'])) {
                        $project->userId = $request['data']['option-user-id'];
                    }
                    $project->save();

                    $company->last_accessed = gmdate("Y-m-d", time());
                    $company->save();

                    if( Storage::disk('input')->exists($folderPrefix . $project['requestFile']) )
                        Storage::disk('input')->delete($folderPrefix . $project['requestFile']);
                    Storage::disk('input')->put($folderPrefix . $project['requestFile'], json_encode($data));
                    
                    // //Backup json file to dropbox
                    $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                    $dropbox = new Dropbox($app);
                    $dropboxFile = new DropboxFile(storage_path('/input/') . $companyNumber. '. ' . $project['companyName'] . '/' . $project['requestFile']);
                    $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_JSON_INPUT') . $companyNumber. '. ' . $project['companyName'] . '/' . $project['requestFile'], ['mode' => 'overwrite']);

                    return response()->json(["message" => "Success!", "status" => true, "projectId" => $project->id, "directory" => $folderPrefix . $project['clientProjectNumber'] . '. ' . $project['clientProjectName'] . ' ' . $request['data']['option-state'] . '/']);
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this project.", "status" => false]);
            }
            else
                return response()->json(["message" => "Cannot find project.", "status" => false]);
        }
        else
        {
            $current_time = time();
            $company = Company::where('id', Auth::user()->companyid)->first();
            $projectId = -1;
            $filename = ($company ? sprintf("%06d", $company['company_number']) : "000000") . "-" . sprintf("%04d", Auth::user()->id) . "-" . $current_time . ".json";
            try {
                $company = Company::where('id', Auth::user()->companyid)->first();
                $companyNumber = $company ? $company['company_number'] : 0;
                $folderPrefix = "/" . $companyNumber. '. ' . $company['company_name'] . '/';
                $data = $this->inputToJson($request['data'], $request['caseCount']);

                $projectState = 0;
                if($request['status'] == 'Saved')
                    $projectState = 1;
                else if($request['status'] == 'Data Check')
                    $projectState = 2;
                else if($request['status'] == 'Submitted')
                    $projectState = 4;

                $project = JobRequest::create([
                    'companyName' => $company['company_name'],
                    'companyId' => Auth::user()->companyid,
                    'userId' => Auth::user()->usernumber,
                    'creator' => Auth::user()->usernumber,
                    'clientProjectName' => $request['data']['txt-project-name'],
                    'clientProjectNumber' => $request['data']['txt-project-number'],
                    'requestFile' => $filename,
                    'planStatus' => 0,
                    'projectState' => $projectState,
                    'analysisType' => 0,
                    'createdTime' => gmdate("Y-m-d\TH:i:s", $current_time),
                    'submittedTime' => gmdate("Y-m-d\TH:i:s", $current_time),
                    'timesDownloaded' => 0,
                    'timesEmailed' => 0,
                    'timesComputed' => 0,
                    'state' => $request['data']['option-state']
                ]);
                $projectId = $project->id;

                $company->last_accessed = gmdate("Y-m-d", time());
                $company->save();
                Storage::disk('input')->put($folderPrefix . $filename, json_encode($data));

                //Backup json file to dropbox
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                $dropboxFile = new DropboxFile(storage_path('/input/') . $companyNumber. '. ' . $company['company_name'] . '/' . $filename);
                $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_JSON_INPUT') . $companyNumber. '. ' . $company['company_name'] . '/' . $filename, ['autorename' => TRUE]);
            }
            catch(Exception $e) {
                return response()->json(["message" => "Failed to generate RS json data file", "status" => false]);
            }

            $companyNumber = $company ? $company['company_number'] : 0;
            return response()->json(["message" => "Success!", "status" => true, "projectId" => $projectId, "directory" => "/" . $companyNumber. '. ' . $project['companyName'] . '/' . $project['clientProjectNumber'] . '. ' . $project['clientProjectName'] . ' ' . $request['data']['option-state'] . '/']);
        }
    }

    public function submitProjectManager(Request $request){
        if($request['projectId'] && $request['projectId'] > 0){
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project){
                $project->userId = $request['userId'];
                $project->save();
                return response()->json(["message" => "Success!", "status" => true]);
            }
            else
                return response()->json(["message" => "Cannot find project.", "status" => false]);
        }
    }

    /**
     * Save Input Datas as files
     *
     * @return JSON
     */
    public function submitPermitInput(Request $request){
        if($request['data'] && $request['data']['projectId'] && $request['data']['projectId'] > 0){
            $job = JobRequest::where('id', $request['data']['projectId'])->first();
            if($job){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $job->companyId == Auth::user()->companyid){
                    $permit = JobPermit::where('job_id', '=', $request['data']['projectId'])->where('filename', '=', $request['filename'])->first();
                    if($permit){
                        $permit->data = json_encode($request['data']);
                        $permit->save();
                        return response()->json(["message" => "Success!", "status" => true]);
                    } else {
                        try {
                            JobPermit::create([
                                'job_id' => $request['data']['projectId'],
                                'filename' => $request['filename'],
                                'data'=> json_encode($request['data'])
                            ]);
                        }
                        catch(Exception $e) {
                            return response()->json(["message" => "Failed to generate Permit json data file", "status" => false]);
                        }
                        return response()->json(["message" => "Success!", "status" => true]);
                    }
                } else
                    return response()->json(["message" => "You don't have permission to edit this project.", "status" => false]);
            } else 
                return response()->json(["message" => "Cannot find the project.", "status" => false]);
        } else 
            return response()->json(["message" => "Please save the job first.", "status" => false]);
    }
    /**
     * Save PDF as files
     *
     * @return JSON
     */
    public function submitPDF(Request $request){
        if($request->data && $request->projectId && $request->projectId > 0){
            $project = JobRequest::where('id', '=', $request->projectId)->first();
            if($project){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $project->companyId == Auth::user()->companyid)
                {
                    $file = PermitFiles::where('id', $request->id)->first();
                    if(!$file)
                        return response()->json(["message" => "PDF File ID mismatch.", "status" => false]);

                    $company = Company::where('id', $project->companyId)->first();
                    $companyNumber = $company ? $company['company_number'] : 0;
                    
                    $folderPrefix = '/' . $companyNumber . ". " . $project['companyName'] . '/';
                    $filepath = $folderPrefix . $project['clientProjectNumber'] . '. ' . $project['clientProjectName'] . ' ' . $project['state'];

                    if($file->formtype == 1){ // Permit
                        $user = User::where('companyid', $project['companyId'])->where('usernumber', $project['userId'])->first();

                        if( Storage::disk('output')->exists($folderPrefix . $request->filename) ) {
                            Storage::disk('output')->delete($folderPrefix . $request->filename);
                        }
                            
                        Storage::disk('output')->put($folderPrefix . $request->filename, file_get_contents($request->data));

                        //Backup pdf file to dropbox
                        try{
                            $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                            $dropbox = new Dropbox($app);
                            $dropboxFile = new DropboxFile(storage_path('output') . '/' . $companyNumber. '. ' . $project['companyName'] . '/' . $request->filename);
                            try{
                                $dropbox->delete(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_OUT') . $filepath. '/' . $request->filename);
                            } catch (DropboxClientException $e) { }
                            $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_OUT') . $filepath. '/' . $request->filename, ['autorename' => false]);
                            $file = $dropbox->getMetadata(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_OUT') . $filepath. '/' . $request->filename);
                            $info = array('name' => $file->getName(), 'id' => $file->getId(), 'type' => 'file', 'path' => env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_OUT') . $filepath. '/' . $request->filename);
                            return response()->json(["message" => "Permit is saved.", "status" => true, "addtotree" => true, "info" => $info]);
                        } catch (DropboxClientException $e) { 
                            $info = array();
                            return response()->json(["message" => "Uploading PDF to dropbox failed!", "status" => false]);
                        }   
                    } else { // PIL
                        $project->PIL_filler_id = Auth::user()->id;
                        $project->save();
                        $filename = $project['clientProjectNumber'] . '. ' . $project['clientProjectName'] . ' ' . $project['state'] . ' - ' . $file->description . '_PIL.pdf';
                        if( Storage::disk('upload')->exists($folderPrefix . $filename) )
                            Storage::disk('upload')->delete($folderPrefix . $filename);
                        Storage::disk('upload')->put($folderPrefix . $filename, file_get_contents($request->data));

                        $flatten = preg_replace('/(\.[^.]+)$/', sprintf('%s$1', '_f'), $filename);
                        if( Storage::disk('upload')->exists($folderPrefix . $flatten) )
                            Storage::disk('upload')->delete($folderPrefix . $flatten);
                        Storage::disk('upload')->put($folderPrefix . $flatten, file_get_contents($request->flattened));

                        //Convert pdf doc 1.7 to 1.5
                        $converted = preg_replace('/(\.[^.]+)$/', sprintf('%s$1', '_f_1.5'), $filename);
                        exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.5 -dNOPAUSE -dBATCH -sOutputFile=\"" . storage_path('/upload/') . $companyNumber. '. ' . $project['companyName'] . '/' . $converted . "\" \"" . storage_path('/upload/') . $companyNumber. '. ' . $project['companyName'] . '/' . $flatten . "\"" );

                        //Backup pdf file to dropbox
                        try{
                            $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                            $dropbox = new Dropbox($app);
                            try{
                                $dropbox->delete(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_INCOPY') . $filepath. '/' . $filename);
                            } catch (DropboxClientException $e) { }
                            $dropboxFile = new DropboxFile(storage_path('upload') . '/'  . $companyNumber. '. ' . $project['companyName'] . '/' . $filename);
                            $dropbox->upload($dropboxFile, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_INCOPY') . $filepath. '/' . $filename, ['autorename' => false]);

                            try{
                                $dropbox->delete(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_INCOPY') . $filepath. '/' . $flatten);
                            } catch (DropboxClientException $e) { }
                            $flattenFile = new DropboxFile(storage_path('upload') . '/' . $companyNumber. '. ' . $project['companyName'] . '/' . $converted);
                            $dropbox->upload($flattenFile, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_INCOPY') . $filepath. '/' . $flatten, ['autorename' => false]);
                            return response()->json(["message" => "Post Installation Letter(PIL) is saved.", "status" => true, "addtotree" => false]);
                        } catch (DropboxClientException $e) { 
                            $info = array();
                            return response()->json(["message" => "Uploading PDF to dropbox failed!", "status" => false]);
                        }  
                    }
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this pdf file.", "status" => false]);
            }
            else
                return response()->json(["message" => "Cannot find project.", "status" => false]);
        } else 
            return response()->json(["message" => "Please save the job first.", "status" => false]);
    }

    /**
     * Return Permit Fields List
     *
     * @return JSON
     */
    public function getPermitFields(Request $request){
        if(!empty($request['filename']) && !empty($request['state'])){
            $fields = PermitFields::where('filename', $request['filename'])->get();
            $permitFile = PermitFiles::where('filename', $request['filename'])->where('state', $request['state'])->first();
            return response()->json(["status" => true, "fields" => $fields, "description" => $permitFile ? $permitFile['description'] : ""]);
        } else
            return response()->json(["status" => false, "message" => "Empty Filename."]);
    }

    /**
     * Save Input Datas as files
     *
     * @return JSON
     */
    private function inputToJson($input, $caseCount, $user = null){
        date_default_timezone_set("America/New_York");
        $data = array();

        //ProgramData
        $data['ProgramData'] = array();
        $data['ProgramData']['Name'] = "iRoof TM";
        $data['ProgramData']['Vendor'] = "Princeton Engineering";
        $data['ProgramData']['Department'] = "Web Services";
        $data['ProgramData']['Version'] = array("Rev" => "3.0.0", "RevDate" => gmdate("m/d/Y", time()));
        $data['ProgramData']['Description'] = "Data Input form for roof structural analysis for Residential Solar Projects";
        $data['ProgramData']['Copyright'] = "Copyright © 2020 Richard Pantel. All Rights Reserved  No parts of this data input form or related calculation reports may be copied in format, content or intent, or reproduced in any form or by any electronic or mechanical means, including information storage and retrieval systems, without permission in writing from the author.  Further, dis-assembly or reverse engineering of this data input form or related calculation reports is strictly prohibited. The author's contact information is: RPantel@Princeton-Engineering.com, web-site: www.Princeton-Engineering.com; tel: 908-507-5500";

        if($user)
            $company = Company::where('id', $user['companyid'])->first();
        else
            $company = Company::where('id', Auth::user()->companyid)->first();
        
        //CompanyInfo
        $data['CompanyInfo'] = array();
        $data['CompanyInfo']['Name'] = $company['company_name'];
        $data['CompanyInfo']['Number'] = $company['company_number'];
        $data['CompanyInfo']['UserId'] = $company['company_number'] . "." . ($user ? $user['usernumber'] : Auth::user()->usernumber);
        $data['CompanyInfo']['Username'] = $user ? $user['username'] : Auth::user()->username;
        $data['CompanyInfo']['UserEmail'] = $user ? $user['email'] : Auth::user()->email;

        //ProjectInfo
        $data['ProjectInfo'] = array();
        $data['ProjectInfo']['Number'] = $input['txt-project-number'];
        $data['ProjectInfo']['Name'] = $input['txt-project-name'];
        $data['ProjectInfo']['Street'] = $input['txt-street-address'];
        $data['ProjectInfo']['City'] = $input['txt-city'];
        $data['ProjectInfo']['State'] = $input['option-state'];
        $data['ProjectInfo']['Zip'] = $input['txt-zip'];
        $data['ProjectInfo']['Type'] = $input['option-project-type'];
        if(isset($input['option-sub-client']))
            $data['ProjectInfo']['SubClient'] = $input['option-sub-client'];
        if(isset($input['txt-sub-project-number']))
            $data['ProjectInfo']['SubProjectNo'] = $input['txt-sub-project-number'];
        
        //Personnel
        $data['Personnel'] = array();
        $data['Personnel']['Name'] = $input['txt-name-of-field-personnel'];
        $data['Personnel']['DateOfFieldVisit'] = $input['date-of-field-visit'];
        $data['Personnel']['DateOfPlanSet'] = $input['date-of-plan-set'];

        //BuildingAge
        $data['BuildingAge'] = $input['txt-building-age'];
        
        //Equipment
        $data['Equipment'] = array();
        $data['Equipment']['PVModule'] = array('Type' => $input['option-module-type'], 'SubType' => $input['option-module-subtype'], 'Option1' => number_format(floatval($input['option-module-option1']), 2), 'Option2' => $input['option-module-option2'], 'Quantity' => $input['option-module-quantity'], 'Custom' => $input['pv-module-custom'], 'CRC32' => $input['pv-module-crc32']);
        $data['Equipment']['PVInverter'] = array('Type' => $input['option-inverter-type'], 'SubType' => $input['option-inverter-subtype'], 'Option1' => number_format(floatval($input['option-inverter-option1']), 2), 'Option2' => $input['option-inverter-option2'], 'Quantity' => $input['option-inverter-quantity'], 'Custom' => $input['inverter-custom'], 'CRC32' => $input['inverter-crc32']);
        $data['Equipment']['Stanchion'] = array('Type' => $input['option-stanchion-type'], 'SubType' => $input['option-stanchion-subtype'], 'Option1' => number_format(floatval($input['option-stanchion-option1']), 2), 'Option2' => $input['option-stanchion-option2'], 'Custom' => $input['stanchion-custom'], 'CRC32' => $input['stanchion-crc32']);
        $data['Equipment']['RailSupportSystem'] = array('Type' => $input['option-railsupport-type'], 'SubType' => $input['option-railsupport-subtype'], 'Option1' => number_format(floatval($input['option-railsupport-option1']), 2), 'Option2' => $input['option-railsupport-option2'], 'Custom' => $input['railsupport-custom'], 'CRC32' => $input['railsupport-crc32']);

        //NumberLoadingConditions = CaseCount
        $data['NumberLoadingConditions'] = $caseCount;

        //LoadingCase Data
        $data['LoadingCase'] = array();
        $number = 1;
        foreach($input['caseInputs'] as $caseInput)
        {
            $caseData = array();
            $caseData['LC_Number'] = $number;
            $caseData['TrussFlag'] = filter_var($caseInput['TrussFlag'], FILTER_VALIDATE_BOOLEAN);
            $caseData['Analysis_type'] = $caseInput['Analysis_type'];
            $caseData['RoofDataInput'] = array(
                "A1" => $number, 
                "A2_feet" => number_format(floatval($caseInput["af-2-{$number}"]), 2), "A2_inches" => number_format(floatval($caseInput["ai-2-{$number}"]), 2), "A2" => number_format(floatval($caseInput["a-2-{$number}"]), 2),
                "A3_feet" => number_format(floatval($caseInput["af-3-{$number}"]), 2), "A3_inches" => number_format(floatval($caseInput["ai-3-{$number}"]), 2), "A3" => number_format(floatval($caseInput["a-3-{$number}"]), 2),
                "A4_feet" => number_format(floatval($caseInput["af-4-{$number}"]), 2), "A4_inches" => number_format(floatval($caseInput["ai-4-{$number}"]), 2), "A4" => number_format(floatval($caseInput["a-4-{$number}"]), 2),
                "A5" => $caseInput["a-5-{$number}"], "A6" => $caseInput["a-6-{$number}"], "A7" => number_format(floatval( isset($caseInput["a-7-{$number}"]) ? $caseInput["a-7-{$number}"] : 0 ), 2), "A7calc" => number_format(floatval(isset($caseInput["ac-7-{$number}"]) ? $caseInput["ac-7-{$number}"] : 0), 2), 
                "A8_feet" => number_format(floatval($caseInput["af-8-{$number}"]), 2), "A8_inches" => number_format(floatval($caseInput["ai-8-{$number}"]), 2), "A8" => number_format(floatval(isset($caseInput["a-8-{$number}"]) ? $caseInput["a-8-{$number}"] : 0), 2), "A8calc" => number_format(floatval(isset($caseInput["ac-8-{$number}"]) ? $caseInput["ac-8-{$number}"] : 0), 2), 
                "A9_feet" => number_format(floatval($caseInput["af-9-{$number}"]), 2), "A9_inches" => number_format(floatval($caseInput["ai-9-{$number}"]), 2), "A9" => number_format(floatval(isset($caseInput["a-9-{$number}"]) ? $caseInput["a-9-{$number}"] : 0), 2), "A9calc" => number_format(floatval(isset($caseInput["ac-9-{$number}"]) ? $caseInput["ac-9-{$number}"] : 0), 2),
                "A10_feet" => number_format(floatval($caseInput["af-10-{$number}"]), 2), "A10_inches" => number_format(floatval($caseInput["ai-10-{$number}"]), 2), "A10" => number_format(floatval(isset($caseInput["a-10-{$number}"]) ? $caseInput["a-10-{$number}"] : 0), 2), "A10calc" => number_format(floatval(isset($caseInput["ac-10-{$number}"]) ? $caseInput["ac-10-{$number}"] : 0), 2),
                "A_calc_algorithm" => $caseInput["calc-algorithm-{$number}"],
                "A11" => number_format(floatval(isset($caseInput["a-11-{$number}"]) ? $caseInput["a-11-{$number}"] : 0), 2),
                "A12" => $caseInput["a-12-{$number}"]);
            $caseData['RafterDataInput'] = array("B1" => number_format(floatval(isset($caseInput["b-1-{$number}"]) ? $caseInput["b-1-{$number}"] : 0), 2), "B2" => number_format(floatval(isset($caseInput["b-2-{$number}"]) ? $caseInput["b-2-{$number}"] : 0), 2), "B3" => number_format(floatval($caseInput["b-3-{$number}"]), 2), "B4" => $caseInput["b-4-{$number}"], "B5" => $caseInput["b-5-{$number}"]);
            $caseData['CollarTieInformation'] = array(
                "C1" => isset($caseInput["c-1-{$number}"]) ? $caseInput["c-1-{$number}"] : "",
                "C2_feet" => number_format(floatval($caseInput["cf-2-{$number}"]), 2), "C2_inches" => number_format(floatval($caseInput["ci-2-{$number}"]), 2), "C2" => number_format(floatval(isset($caseInput["c-2-{$number}"]) ? $caseInput["c-2-{$number}"] : 0), 2),
                "C3" => number_format(floatval(isset($caseInput["c-3-{$number}"]) ? $caseInput["c-3-{$number}"] : 0), 2),
                "C4_feet" => number_format(floatval($caseInput["cf-4-{$number}"]), 2), "C4_inches" => number_format(floatval($caseInput["ci-4-{$number}"]), 2), "C4" => number_format(floatval(isset($caseInput["c-4-{$number}"]) ? $caseInput["c-4-{$number}"] : 0), 2));
            $caseData['RoofDeckSurface'] = array("D0" => $caseInput["d-0-{$number}"], "D1" => number_format(floatval($caseInput["d-1-{$number}"]), 2), "D2" => $caseInput["d-2-{$number}"], "D3" => $caseInput["d-3-{$number}"], "D4" => $caseInput["d-4-{$number}"], "D5" => $caseInput["d-5-{$number}"], "D6" => $caseInput["d-6-{$number}"], "D7" => $caseInput["d-7-{$number}"], "D8" => $caseInput["d-8-{$number}"], "D9" => $caseInput["d-9-{$number}"] ? $caseInput["d-9-{$number}"] : "");
            $caseData['Location'] = array(
                "E1_feet" => number_format(floatval($caseInput["ef-1-{$number}"]), 2), "E1_inches" => number_format(floatval($caseInput["ei-1-{$number}"]), 2), "E1" => number_format(floatval($caseInput["e-1-{$number}"]), 2),
                "E2_feet" => number_format(floatval($caseInput["ef-2-{$number}"]), 2), "E2_inches" => number_format(floatval($caseInput["ei-2-{$number}"]), 2), "E2" => number_format(floatval($caseInput["e-2-{$number}"]), 2));
            $caseData['NumberOfModules'] = array("F1" => $caseInput["f-1-{$number}"]);
            $caseData['NSGap'] = array("G1" => number_format(floatval($caseInput["g-1-{$number}"]), 2), "G2" => number_format(floatval($caseInput["g-2-{$number}"]), 2));
            $caseData['ModuleRelativeTilt'] = array("G1" => number_format(floatval($caseInput["g-1-{$number}"]), 2));
            $caseData['RotateModuleOrientation'] = array("H1" => filter_var($caseInput["h-1-{$number}"], FILTER_VALIDATE_BOOLEAN), "H2" => filter_var($caseInput["h-2-{$number}"], FILTER_VALIDATE_BOOLEAN), "H3" => filter_var($caseInput["h-3-{$number}"], FILTER_VALIDATE_BOOLEAN), "H4" => filter_var($caseInput["h-4-{$number}"], FILTER_VALIDATE_BOOLEAN), "H5" => filter_var($caseInput["h-5-{$number}"], FILTER_VALIDATE_BOOLEAN), "H6" => filter_var($caseInput["h-6-{$number}"], FILTER_VALIDATE_BOOLEAN), "H7" => filter_var($caseInput["h-7-{$number}"], FILTER_VALIDATE_BOOLEAN), "H8" => filter_var($caseInput["h-8-{$number}"], FILTER_VALIDATE_BOOLEAN), "H9" => filter_var($caseInput["h-9-{$number}"], FILTER_VALIDATE_BOOLEAN), "H10" => filter_var($caseInput["h-10-{$number}"], FILTER_VALIDATE_BOOLEAN), "H11" => filter_var($caseInput["h-11-{$number}"], FILTER_VALIDATE_BOOLEAN), "H12" => filter_var($caseInput["h-12-{$number}"], FILTER_VALIDATE_BOOLEAN));
            $caseData['Notes'] = array("I1" => $caseInput["i-1-{$number}"] ? $caseInput["i-1-{$number}"] : "");

            $caseData['TrussDataInput'] = array();
            $caseData['TrussDataInput']['RoofSlope'] = array('Type' => $caseInput["option-roof-slope-{$number}"], 'Degree' => number_format(floatval($caseInput["txt-roof-degree-{$number}"]), 2), 'UnknownDegree' => number_format(floatval($caseInput["td-unknown-degree1-{$number}"]), 2), 'CalculatedRoofPlaneLength' => number_format(floatval($caseInput["td-calculated-roof-plane-length-{$number}"]), 2), 'td-diff-between-measured-and-calculated' => number_format(floatval($caseInput["td-diff-between-measured-and-calculated-{$number}"]), 2));
            $caseData['TrussDataInput']['RoofPlane'] = array('MemberType' => $caseInput["option-roof-member-type-{$number}"], 'Length_feet' => number_format(floatval($caseInput["txt-length-of-roof-plane-f-{$number}"]), 2), 'Length_inches' => number_format(floatval($caseInput["txt-length-of-roof-plane-i-{$number}"]), 2), 'Length' => number_format(floatval($caseInput["txt-length-of-roof-plane-{$number}"]), 2), 'NumberOfSegments' => $caseInput["option-number-segments1-{$number}"], 'SumOfLengthsEntered' => number_format(floatval($caseInput["td-sum-of-length-entered-{$number}"]), 2), 'ChecksumOfChordLength' => $caseInput["td-checksum-of-segment1-{$number}"]);
            if( isset($caseInput["txt-roof-segment1-length-{$number}"]) )
            {
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment1_feet'] = number_format(floatval($caseInput["txt-roof-segment1-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment1_inches'] = number_format(floatval($caseInput["txt-roof-segment1-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment1'] = number_format(floatval($caseInput["txt-roof-segment1-length-{$number}"]), 2);
            } 
            if( isset($caseInput["txt-roof-segment2-length-{$number}"]) ){
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment2_feet'] = number_format(floatval($caseInput["txt-roof-segment2-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment2_inches'] = number_format(floatval($caseInput["txt-roof-segment2-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment2'] = number_format(floatval($caseInput["txt-roof-segment2-length-{$number}"]), 2);
            } 
            if( isset($caseInput["txt-roof-segment3-length-{$number}"]) ){
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment3_feet'] = number_format(floatval($caseInput["txt-roof-segment3-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment3_inches'] = number_format(floatval($caseInput["txt-roof-segment3-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment3'] = number_format(floatval($caseInput["txt-roof-segment3-length-{$number}"]), 2);
            } 
            if( isset($caseInput["txt-roof-segment4-length-{$number}"]) ){
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment4_feet'] = number_format(floatval($caseInput["txt-roof-segment4-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment4_inches'] = number_format(floatval($caseInput["txt-roof-segment4-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment4'] = number_format(floatval($caseInput["txt-roof-segment4-length-{$number}"]), 2);
            } 
            if( isset($caseInput["txt-roof-segment5-length-{$number}"]) ){
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment5_feet'] = number_format(floatval($caseInput["txt-roof-segment5-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment5_inches'] = number_format(floatval($caseInput["txt-roof-segment5-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment5'] = number_format(floatval($caseInput["txt-roof-segment5-length-{$number}"]), 2);
            } 
            if( isset($caseInput["txt-roof-segment6-length-{$number}"]) ){
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment6_feet'] = number_format(floatval($caseInput["txt-roof-segment6-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment6_inches'] = number_format(floatval($caseInput["txt-roof-segment6-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment6'] = number_format(floatval($caseInput["txt-roof-segment6-length-{$number}"]), 2);
            } 
            $caseData['TrussDataInput']['FloorPlane'] = array('MemberType' => $caseInput["option-floor-member-type-{$number}"], 'Length_feet' => number_format(floatval($caseInput["txt-length-of-floor-plane-f-{$number}"]), 2), 'Length_inches' => number_format(floatval($caseInput["txt-length-of-floor-plane-i-{$number}"]), 2), 'Length' => number_format(floatval($caseInput["txt-length-of-floor-plane-{$number}"]), 2), 'NumberOfSegments' => $caseInput["option-number-segments2-{$number}"], 'SumOfLengthsEntered' => number_format(floatval($caseInput["td-total-length-entered-{$number}"]), 2), 'ChecksumOfChordLength' => $caseInput["td-checksum-of-segment2-{$number}"]);
            if( isset($caseInput["txt-floor-segment1-length-{$number}"]) ){
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment1_feet'] = number_format(floatval($caseInput["txt-floor-segment1-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment1_inches'] = number_format(floatval($caseInput["txt-floor-segment1-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment1'] = number_format(floatval($caseInput["txt-floor-segment1-length-{$number}"]), 2);
            } 
            if( isset($caseInput["txt-floor-segment2-length-{$number}"]) ){
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment2_feet'] = number_format(floatval($caseInput["txt-floor-segment2-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment2_inches'] = number_format(floatval($caseInput["txt-floor-segment2-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment2'] = number_format(floatval($caseInput["txt-floor-segment2-length-{$number}"]), 2);
            } 
            if( isset($caseInput["txt-floor-segment3-length-{$number}"]) ){
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment3_feet'] = number_format(floatval($caseInput["txt-floor-segment3-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment3_inches'] = number_format(floatval($caseInput["txt-floor-segment3-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment3'] = number_format(floatval($caseInput["txt-floor-segment3-length-{$number}"]), 2);
            } 
            if( isset($caseInput["txt-floor-segment4-length-{$number}"]) ){
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment4_feet'] = number_format(floatval($caseInput["txt-floor-segment4-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment4_inches'] = number_format(floatval($caseInput["txt-floor-segment4-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment4'] = number_format(floatval($caseInput["txt-floor-segment4-length-{$number}"]), 2);
            } 
            if( isset($caseInput["txt-floor-segment5-length-{$number}"]) ){
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment5_feet'] = number_format(floatval($caseInput["txt-floor-segment5-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment5_inches'] = number_format(floatval($caseInput["txt-floor-segment5-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment5'] = number_format(floatval($caseInput["txt-floor-segment5-length-{$number}"]), 2);
            } 
            if( isset($caseInput["txt-floor-segment6-length-{$number}"]) ){
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment6_feet'] = number_format(floatval($caseInput["txt-floor-segment6-length-f-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment6_inches'] = number_format(floatval($caseInput["txt-floor-segment6-length-i-{$number}"]), 2);
                $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment6'] = number_format(floatval($caseInput["txt-floor-segment6-length-{$number}"]), 2);
            } 

            $caseData['Diagonal1'] = array();
            if( isset($caseInput["option-diagonals-mem1-1-type-{$number}"]) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput["diag-1-1-{$number}"] == 'on' ? false : true, "memType" => $caseInput["option-diagonals-mem1-1-type-{$number}"], "memId" => intval($caseInput["td-diag-1-1-{$number}"])) );
            if( isset($caseInput["option-diagonals-mem1-2-type-{$number}"]) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput["diag-1-2-{$number}"] == 'on' ? false : true, "memType" => $caseInput["option-diagonals-mem1-2-type-{$number}"], "memId" => intval($caseInput["td-diag-1-2-{$number}"])) );
            if( isset($caseInput["option-diagonals-mem1-3-type-{$number}"]) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput["diag-1-3-{$number}"] == 'on' ? false : true, "memType" => $caseInput["option-diagonals-mem1-3-type-{$number}"], "memId" => intval($caseInput["td-diag-1-3-{$number}"])) );
            if( isset($caseInput["option-diagonals-mem1-4-type-{$number}"]) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput["diag-1-4-{$number}"] == 'on' ? false : true, "memType" => $caseInput["option-diagonals-mem1-4-type-{$number}"], "memId" => intval($caseInput["td-diag-1-4-{$number}"])) );
            if( isset($caseInput["option-diagonals-mem1-5-type-{$number}"]) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput["diag-1-5-{$number}"] == 'on' ? false : true, "memType" => $caseInput["option-diagonals-mem1-5-type-{$number}"], "memId" => intval($caseInput["td-diag-1-5-{$number}"])) );
            if( isset($caseInput["option-diagonals-mem1-6-type-{$number}"]) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput["diag-1-6-{$number}"] == 'on' ? false : true, "memType" => $caseInput["option-diagonals-mem1-6-type-{$number}"], "memId" => intval($caseInput["td-diag-1-6-{$number}"])) );

            $caseData['Diagonal2'] = array();
            if( isset($caseInput["option-diagonals-mem2-1-type-{$number}"]) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput["diag-2-1-{$number}"] == 'on' ? false : true, "reverse" => $caseInput["diag-2-reverse-1-{$number}"] == 'on' ? true : false, "memType" => $caseInput["option-diagonals-mem2-1-type-{$number}"], "memId" => intval($caseInput["td-diag-2-1-{$number}"])) );
            if( isset($caseInput["option-diagonals-mem2-2-type-{$number}"]) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput["diag-2-2-{$number}"] == 'on' ? false : true, "reverse" => $caseInput["diag-2-reverse-2-{$number}"] == 'on' ? true : false, "memType" => $caseInput["option-diagonals-mem2-1-type-{$number}"], "memId" => intval($caseInput["td-diag-2-2-{$number}"])) );
            if( isset($caseInput["option-diagonals-mem2-3-type-{$number}"]) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput["diag-2-3-{$number}"] == 'on' ? false : true, "reverse" => $caseInput["diag-2-reverse-3-{$number}"] == 'on' ? true : false, "memType" => $caseInput["option-diagonals-mem2-1-type-{$number}"], "memId" => intval($caseInput["td-diag-2-3-{$number}"])) );
            if( isset($caseInput["option-diagonals-mem2-4-type-{$number}"]) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput["diag-2-4-{$number}"] == 'on' ? false : true, "reverse" => $caseInput["diag-2-reverse-4-{$number}"] == 'on' ? true : false, "memType" => $caseInput["option-diagonals-mem2-1-type-{$number}"], "memId" => intval($caseInput["td-diag-2-4-{$number}"])) );
            if( isset($caseInput["option-diagonals-mem2-5-type-{$number}"]) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput["diag-2-5-{$number}"] == 'on' ? false : true, "reverse" => $caseInput["diag-2-reverse-5-{$number}"] == 'on' ? true : false, "memType" => $caseInput["option-diagonals-mem2-1-type-{$number}"], "memId" => intval($caseInput["td-diag-2-5-{$number}"])) );
            if( isset($caseInput["option-diagonals-mem2-6-type-{$number}"]) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput["diag-2-6-{$number}"] == 'on' ? false : true, "reverse" => $caseInput["diag-2-reverse-6-{$number}"] == 'on' ? true : false, "memType" => $caseInput["option-diagonals-mem2-1-type-{$number}"], "memId" => intval($caseInput["td-diag-2-6-{$number}"])) );

            array_push($data['LoadingCase'], $caseData);
            $number ++;
        }

        //Overrides
        $data['Wind'] = number_format(floatval($input['wind-speed']), 1);
        $data['WindCheckbox'] = $input['wind-speed-override'] == "true" ? true : false;
        $data['Snow'] = number_format(floatval($input['ground-snow']), 1);
        $data['SnowCheckbox'] = $input['ground-snow-override'] == "true" ? true : false;
        $data['IBC'] = $input['ibc-year'];
        $data['ASCE'] = $input['asce-year'];
        $data['NEC'] = $input['nec-year'];
        $data['WindExposure'] = $input['wind-exposure'];
        $data['Units'] = $input['override-unit'];

        return $data;
    }

    /**
     * Show the list of projects.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function projectList(){
        $companyList = Company::orderBy('company_name', 'asc')->get();
        $projectStatusList = JobProjectStatus::orderBy('id', 'asc')->get();
        $planStatusList = JobPlanStatus::orderBy('id', 'asc')->get();
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
            $users = User::get(array('id', 'username'));
        else
            $users = User::where('companyid', Auth::user()->companyid)->get(array('id', 'username'));

        $company = Company::where('id', Auth::user()->companyid)->first();
        return view('general.projectlist')
                ->with('companyList', $companyList)
                ->with('planStatusList', $planStatusList)
                ->with('projectStatusList', $projectStatusList)
                ->with('users', $users)
                ->with('companyName', $company? $company->company_name : '');
    }

    //protected $globalStates = array("None", "Saved", "Check Requested", "Reviewed", "Submitted", "Report Prepared", "Plan Requested", "Plan Reviewed", "Link Sent", "Completed");
    //protected $globalStatus = array("No action", "Plans uploaded to portal", "Plans reviewed", "Comments issued", "Updated plans uploaded to portal", "Revised comments issued", "Final plans uploaded to portal", "PE sealed plans link sent");
    //protected $stateColors = array("danger", "primary", "info", "warning", "primary", "info", "primary", "dark", "secondary", "success");
    //protected $statusColors = array("danger", "primary", "info", "warning", "primary", "dark", "secondary", "success");

    /**
     * Return the result of server-side rendering
     *
     * @return JSON
     */
    public function getProjectList(Request $request){
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
        {
            $handler = new JobRequest;
            $columns = array( 
                0 =>'idx',
                1 =>'id', 
                2 =>'job_request.companyId',
                3 =>'userId',
                4 =>'clientProjectNumber',
                5 =>'clientProjectName',
                6 => 'state',
                7 =>'requestFile',
                8 =>'createdTime',
                9 =>'submittedTime',
                10 =>'projectState',
                11 =>'planStatus',
            );
        }
        else
        {
            //if(Auth::user()->userrole != 3)
            $handler = JobRequest::where('job_request.companyId', Auth::user()->companyid);
            //else
                //$handler = new JobRequest;
            $columns = array( 
                0 =>'idx',
                1 =>'userId',
                2 =>'clientProjectNumber',
                3 =>'clientProjectName',
                4 => 'state',
                5 =>'createdTime',
                6 =>'submittedTime',
                7 =>'projectState',
                8 =>'planStatus',
            );
        }
        
        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $handler = $handler->leftjoin('company_info', "company_info.id", "=", "job_request.companyId")
            ->leftjoin('users', function($join){
                $join->on('job_request.companyId', '=', 'users.companyid');
                $join->on('job_request.userId', '=', 'users.usernumber');
            })
            ->leftjoin('job_planstatus', "job_planstatus.id", "=", "job_request.planStatus")
            ->leftjoin('job_pstatus', "job_pstatus.id", "=", "job_request.projectState");

        // sort by company when $order == 'userId'
        if($order == 'userId')
        {
            $handler = $handler->orderBy('job_request.companyId', $dir);
        }

        // filter created_from
        if(!empty($request->input("created_from")) && $request->input("created_from") != "")
        {
            $date = new DateTime($request->input("created_from"), new DateTimeZone('EST'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $handler = $handler->where('job_request.createdTime', '>=', $date->format("Y-m-d H:i:s"));
        }
        // filter created_to
        if(!empty($request->input("created_to")) && $request->input("created_to") != "")
        {
            $dateTo = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($request->input("created_to"))));
            $date = new DateTime($dateTo, new DateTimeZone('EST'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $handler = $handler->where('job_request.createdTime', '<=', $date->format("Y-m-d H:i:s"));
        }
        // filter submitted_from
        if(!empty($request->input("submitted_from")) && $request->input("submitted_from") != "")
        {
            $date = new DateTime($request->input("submitted_from"), new DateTimeZone('EST'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $handler = $handler->where('job_request.submittedTime', '>=', $date->format("Y-m-d H:i:s"));
        }
        // filter submitted_to
        if(!empty($request->input("submitted_to")) && $request->input("submitted_to") != "")
        {
            $dateTo = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($request->input("submitted_to"))));
            $date = new DateTime($dateTo, new DateTimeZone('EST'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $handler = $handler->where('job_request.submittedTime', '<=', $date->format("Y-m-d H:i:s"));
        }
        // filter plancheck
        if(!empty($request->input("plancheck")) && $request["plancheck"] == 1){
            $handler = $handler->where('job_request.planCheck', 1);
        }
        // filter asbuilt
        if(!empty($request->input("asbuilt")) && $request["asbuilt"] == 1){
            $handler = $handler->where('job_request.asBuilt', 1);
        }
        // filter PIL
        if(!empty($request->input("pil")) && $request["pil"] == 1){
            $handler = $handler->where('job_request.PIL_status', 1);
        }
        
        // admin filter company name, user, project name, project number, project state, plan status
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4){
            if(!empty($request->input("columns.2.search.value")))
                $handler = $handler->where('company_info.company_name', 'LIKE', "%{$request->input("columns.2.search.value")}%");
            if(!empty($request->input("columns.3.search.value")))
                $handler = $handler->where('users.username', 'LIKE', "%{$request->input('columns.3.search.value')}%");
            if(!empty($request->input("columns.4.search.value")))
                $handler = $handler->where('job_request.clientProjectNumber', 'LIKE', "%{$request->input('columns.4.search.value')}%");
            if(!empty($request->input("columns.5.search.value")))
                $handler = $handler->where('job_request.clientProjectName', 'LIKE', "%{$request->input('columns.5.search.value')}%");
            if(isset($request["columns.6.search.value"]))
                $handler = $handler->where('job_request.state', 'LIKE', "%{$request->input('columns.6.search.value')}%");
            if(isset($request["columns.10.search.value"]))
                $handler = $handler->where('job_request.projectState', 'LIKE', "%{$request->input('columns.10.search.value')}%");
            if(isset($request["columns.11.search.value"]))
                $handler = $handler->where('job_request.planStatus', 'LIKE', "%{$request->input('columns.11.search.value')}%");
            // filter chatIcon
            if(!empty($request["columns.12.search.value"]) && $request["columns.12.search.value"] != "")
            {
                $handler = $handler->leftjoin('job_chat', function($join){
                    $join->on('job_request.id', '=', 'job_chat.jobId')->whereRaw('job_chat.id IN (select MAX(job_chat.id) from job_chat join job_request on job_request.id = job_chat.jobId group by job_request.id )');
                });
                $userIds = User::where('companyid', $request["columns.12.search.value"])->get('id')->toArray();
                $handler = $handler->whereIn('job_chat.userId', $userIds);
            }
        }
        else{ // client filter user, project name, project number
            if(!empty($request->input("columns.1.search.value")))
                $handler = $handler->where('users.username', 'LIKE', "%{$request->input('columns.1.search.value')}%");
            if(!empty($request->input("columns.2.search.value")))
                $handler = $handler->where('job_request.clientProjectNumber', 'LIKE', "%{$request->input('columns.2.search.value')}%");
            if(!empty($request->input("columns.3.search.value")))
                $handler = $handler->where('job_request.clientProjectName', 'LIKE', "%{$request->input('columns.3.search.value')}%");
            if(isset($request["columns.4.search.value"]))
                $handler = $handler->where('job_request.state', 'LIKE', "%{$request->input('columns.4.search.value')}%");
            if(isset($request["columns.7.search.value"]))
                $handler = $handler->where('job_request.projectState', 'LIKE', "%{$request->input('columns.7.search.value')}%");
            if(isset($request["columns.8.search.value"]))
                $handler = $handler->where('job_request.planStatus', 'LIKE', "%{$request->input('columns.8.search.value')}%");
            // filter chatIcon
            if(!empty($request["columns.9.search.value"]) && $request["columns.9.search.value"] != ""){
                $handler = $handler->leftjoin('job_chat', function($join){
                    $join->on('job_request.id', '=', 'job_chat.jobId')->whereRaw('job_chat.id IN (select MAX(job_chat.id) from job_chat join job_request on job_request.id = job_chat.jobId group by job_request.id )');
                });
                $handler = $handler->where('job_chat.userId', $request["columns.9.search.value"]);
            }
        }

        if(empty($request->input('search.value')))
        {            
            $totalFiltered = $handler->count();
            $jobs = $handler->offset($start)->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array(
                        'job_request.id as id',
                        'company_info.company_name as companyname',
                        'users.username as username',
                        'job_request.clientProjectName as projectname',
                        'job_request.clientProjectNumber as projectnumber',
                        'job_request.requestFile as requestfile',
                        'job_request.createdTime as createdtime',
                        'job_request.submittedTime as submittedtime',
                        'job_request.projectState as projectstate',
                        'job_request.planStatus as planstatus',
                        'job_request.state as state',
                        'job_request.planCheck as plancheck',
                        'job_request.chatIcon as chatIcon',
                        'job_request.asBuilt as asbuilt',
                        'job_planstatus.color as statuscolor',
                        'job_pstatus.color as statecolor',
                        'job_request.eSeal as eSeal',
                        'job_request.eSeal_asbuilt as eSeal_asbuilt',
                        'job_request.PIL_status as PIL_status',
                        'job_request.eSeal_PIL as eSeal_PIL'
                    )
                );
            //if($handler->offset($start)->count() > 0)
                
        }
        else {
            $search = $request->input('search.value'); 
            $jobs =  $handler->where(function($q) use ($search) {
                            $q->where('job_request.id','LIKE',"%{$search}%")
                                ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                                ->orWhere('users.username', 'LIKE',"%{$search}%")
                                ->orWhere('job_request.clientProjectName', 'LIKE',"%{$search}%")
                                ->orWhere('job_request.clientProjectNumber', 'LIKE',"%{$search}%")
                                ->orWhere('job_request.createdTime', 'LIKE',"%{$search}%")
                                ->orWhere('job_request.submittedTime', 'LIKE',"%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array(
                                'job_request.id as id',
                                'company_info.company_name as companyname',
                                'users.username as username',
                                'job_request.clientProjectName as projectname',
                                'job_request.clientProjectNumber as projectnumber',
                                'job_request.requestFile as requestfile',
                                'job_request.createdTime as createdtime',
                                'job_request.submittedTime as submittedtime',
                                'job_request.projectState as projectstate',
                                'job_request.planStatus as planstatus',
                                'job_request.state as state',
                                'job_request.planCheck as plancheck',
                                'job_request.chatIcon as chatIcon',
                                'job_request.asBuilt as asbuilt',
                                'job_planstatus.color as statuscolor',
                                'job_pstatus.color as statecolor',
                                'job_request.eSeal as eSeal',
                                'job_request.eSeal_asbuilt as eSeal_asbuilt',
                                'job_request.PIL_status as PIL_status',
                                'job_request.eSeal_PIL as eSeal_PIL'
                            )
                        );

            $totalFiltered = $handler->where(function($q) use ($search) {
                    $q->where('job_request.id','LIKE',"%{$search}%")
                    ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                    ->orWhere('users.username', 'LIKE',"%{$search}%")
                    ->orWhere('job_request.clientProjectName', 'LIKE',"%{$search}%")
                    ->orWhere('job_request.clientProjectNumber', 'LIKE',"%{$search}%")
                    ->orWhere('job_request.createdTime', 'LIKE',"%{$search}%")
                    ->orWhere('job_request.submittedTime', 'LIKE',"%{$search}%");
                })->count();
        }

        $data = array();

        if(!empty($jobs))
        {
            $idx = 1;
            foreach ($jobs as $job)
            {
                $nestedData['idx'] = $idx; $idx ++;
                $nestedData['id'] = $job->id;
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
                {
                    $nestedData['companyname'] = $job->companyname;
                    $nestedData['requestfile'] = $job->requestfile;
                }
                $nestedData['username'] = $job->username;
                $nestedData['projectname'] = $job->projectname;
                $nestedData['projectnumber'] = $job->projectnumber;
                $nestedData['state'] = $job->state;
                $date = new DateTime($job->createdtime, new DateTimeZone('UTC'));
                $date->setTimezone(new DateTimeZone('EST'));
                $nestedData['createdtime'] = $date->format("Y-m-d H:i:s");

                $date = new DateTime($job->submittedtime, new DateTimeZone('UTC'));
                $date->setTimezone(new DateTimeZone('EST'));
                $nestedData['submittedtime'] = $date->format("Y-m-d H:i:s");
                
                $globalStates = JobProjectStatus::orderBy('id', 'asc')->get();
                $globalStatus = JobPlanStatus::orderBy('id', 'asc')->get();

                $statenote = isset($globalStates[intval($job->projectstate)]) ? $globalStates[intval($job->projectstate)]->notes : 'Unknown State';
                $statusnote = isset($globalStatus[intval($job->planstatus)]) ? $globalStatus[intval($job->planstatus)]->notes : 'Unknown Status';
                $nestedData['statenote'] = $statenote;
                $nestedData['statusnote'] = $statusnote;
                if(!isset($globalStates[intval($job->projectstate)])) $job->statecolor = '#ff0000';
                if(!isset($globalStatus[intval($job->planstatus)])) $job->statuscolor = '#ff0000';

                if(Auth::user()->userrole == 2){
                    $nestedData['projectstate'] = "<span class='badge dropdown-toggle job-dropdown' style='color: black; background-color: {$job->statecolor};' id='state_{$job->id}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> {$statenote} </span>";

                    $nestedData['projectstate'] .= "<div class='dropdown-menu' aria-labelledby='state_{$job->id}'>";
                    $i = 0;
                    foreach($globalStates as $state){
                        $nestedData['projectstate'] .= "<a class='dropdown-item' href='javascript:changeState({$job->id}, {$i})' style='color: black; background-color: {$state->color};'>{$state->notes}</a>";
                        $i ++;
                    }
                    $nestedData['projectstate'] .= "</div>";

                    $nestedData['planstatus'] = "<span class='badge dropdown-toggle job-dropdown' style='color: black; background-color: {$job->statuscolor};' id='status_{$job->id}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> {$statusnote} </span>";

                    $nestedData['planstatus'] .= "<div class='dropdown-menu' aria-labelledby='status_{$job->id}'>";
                    $i = 0;
                    foreach($globalStatus as $status){
                        $nestedData['planstatus'] .= "<a class='dropdown-item' href='javascript:changeStatus({$job->id}, {$i})' style='color: black; background-color: {$status->color};'>{$status->notes}</a>";
                        $i ++;
                    }
                    $nestedData['planstatus'] .= "</div>";
                }
                else{
                    $nestedData['projectstate'] = "<span class='badge' style='white-space: pre-wrap; color: black; background-color: {$job->statecolor};'> {$statenote} </span>";                
                    $nestedData['planstatus'] = "<span class='badge' style='white-space: pre-wrap; color: black; background-color: {$job->statuscolor};'> {$statusnote} </span>";                
                }

                if($job->chatIcon == 0)
                    $chatbadge = 'warning';
                else if($job->chatIcon == 1)
                    $chatbadge = 'alt-warning';
                else if($job->chatIcon == 2)
                    $chatbadge = 'info';
                else if($job->chatIcon == 3)
                    $chatbadge = 'danger';
                else if($job->chatIcon == 4)
                    $chatbadge = 'success';
                else
                    $chatbadge = 'warning';
                
                if($job->eSeal == 1)
                    $sealCol = 'ffc34b';
                else if($job->eSeal == 2)
                    $sealCol = '00FF00';
                else
                    $sealCol = '000000';
                
                if($job->eSeal_asbuilt == 1)
                    $asbuiltCol = 'ffc34b';
                else if($job->eSeal_asbuilt == 2)
                    $asbuiltCol = '00FF00';
                else
                    $asbuiltCol = '000000';

                if($job->eSeal_PIL == 1)
                    $pilCol = 'ffc34b';
                else if($job->eSeal_PIL == 2)
                    $pilCol = '00FF00';
                else
                    $pilCol = '000000';

                $nestedData['actions'] = "
                <div class='text-center' style='display: flex; align-items: center; justify-content: center;'>
                    <a href='rsinput?projectId={$nestedData['id']}' class='btn btn-primary mr-1' style='padding: 3px 4px;'>
                        <i class='fa fa-pencil-alt'></i>
                    </a>" . 
                    "<a href='jobchat?projectId={$nestedData['id']}' class='mr-2 btn btn-" . $chatbadge . "' style='padding: 3px 4px;'>
                        <i class='fab fa-rocketchat'></i>
                    </a>". 
                    "<input class='mr-1 plancheck' type='checkbox' " . (Auth::user()->userrole == 4 ? "style='pointer-events: none;'" : "onchange='togglePlanCheck(this, {$job['id']})'") . ($job['plancheck'] == 1 ? " checked" : "") . ">" . 
                    "<input class='mr-1 asbuilt' type='checkbox' " . (Auth::user()->userrole == 4 ? "style='pointer-events: none;'" : "onchange='toggleAsBuilt(this, {$job['id']})'") . ($job['asbuilt'] == 1 ? " checked" : "") . ">" . 
                    (Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->allow_permit > 0 ? "<input class='mr-2 pilcheck' type='checkbox'" . (Auth::user()->userrole == 4 ? "style='pointer-events: none;'" : "onchange='togglePilStatus(this, {$job['id']})'") . ($job['PIL_status'] == 1 ? " checked" : "") . ">" : "") .
                    (Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4? "<button onclick='openReviewTab({$job['id']})' class='mr-1 btn' style='padding: 7px 4px; background-image: -webkit-linear-gradient(-90deg, #{$sealCol} 0%, #{$sealCol} 30%, #FFFFFF 31%, #FFFFFF 35%, #{$asbuiltCol} 36%, #{$asbuiltCol} 65%, #FFFFFF 66%, #FFFFFF 72%, #{$pilCol} 71%, #{$pilCol} 100%); border: 1px solid white;'>
                        <div style='width:16px; height: 16px;'></div>
                    </a>" : "") . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='js-swal-confirm btn btn-danger mr-1' onclick='delProject(this,{$nestedData['id']})' style='padding: 3px 4px;'>
                    <i class='fa fa-trash'></i>
                </button>" : "") .
                    "";
                $data[] = $nestedData;
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
     * Return the json file content.
     *
     * @return JSON or TEXT
     */
    public function requestFile(Request $request){
        $job = JobRequest::where('id', $request['jobId'])->first();
        if($job)
        {
            if(Auth::user()->userrole != 2 && Auth::user()->companyid != $job['companyId'])
                return response()->json("You do not have any role to view this project.");
            
            $company = Company::where('id', $job->companyId)->first();
            $companyNumber = $company ? $company['company_number'] : 0;
            $folderPrefix = "/" . $companyNumber. '. ' . $job['companyName'] . '/';
            if( Storage::disk('input')->exists($folderPrefix . $job['requestFile']) )
                return Storage::disk('input')->get($folderPrefix . $job['requestFile']);
            else
                return "Sorry, We cannot find the file.";
        }
        else
            return "Sorry, We cannot find the file.";
    }

    /**
     * Return the list of cec pv modules.
     *
     * @return JSON
     */
    public function getCECPVModules(Request $request){
        $cec_modules = PVModuleCEC::all();
        $cec_modules = $cec_modules->toArray();
        return json_encode($cec_modules);
    }

    /**
     * Return the list of pv modules.
     *
     * @return JSON
     */
    public function getPVModules(Request $request){
        $pv_modules = PVModule::all();
        $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 0)->first();
        if(!$favorite)
            $favorite_ids = '';
        else
            $favorite_ids = $favorite->crc32_ids;
        $favorites = explode(",", $favorite_ids);
        foreach($pv_modules as $module){
            if(in_array(strval($module['crc32']), $favorites))
                $module['favorite'] = true;
        }
        $pv_modules = $pv_modules->toArray();

        //Custom
        if(Auth::user()->userrole != 2){
            $custom_modules = CustomModule::where('client_no', Auth::user()->companyid)->get(
                array('mfr', 'model', 'rating', 'length', 'width', 'depth', 'weight', 'favorite', 'crc32')
            );
        } else {
            $custom_modules = CustomModule::get(
                array('mfr', 'model', 'rating', 'length', 'width', 'depth', 'weight', 'favorite', 'crc32')
            );
        }
        foreach($custom_modules as $module){
            $module['custom'] = true;
            $pv_modules[] = $module;
        }
        usort($pv_modules, function($a, $b) {
            if(strcasecmp($a['mfr'], $b['mfr']) < 0) return -1;
            if(strcasecmp($a['mfr'], $b['mfr']) > 0) return 1;
            return strcasecmp($a['model'], $b['model']);
        });

        return json_encode($pv_modules);
    }

    /**
     * Return the list of pv inverters.
     *
     * @return JSON
     */
    public function getPVInverters(Request $request){
        $pv_inverters = PVInverter::all();
        $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 1)->first();
        if(!$favorite)
            $favorite_ids = '';
        else
            $favorite_ids = $favorite->crc32_ids;
        $favorites = explode(",", $favorite_ids);
        foreach($pv_inverters as $inverter){
            if(in_array(strval($inverter['crc32']), $favorites))
                $inverter['favorite'] = true;
        }
        $pv_inverters = $pv_inverters->toArray();

        //Custom

        if(Auth::user()->userrole != 2){
            $custom_inverters = CustomInverter::where('client_no', Auth::user()->companyid)->get(
                array('mfr as module', 'model as submodule', 'rating as option1', 'favorite', 'crc32', 'Rated_Out_Power as watts')
            );
        } else {
            $custom_inverters = CustomInverter::get(
                array('mfr as module', 'model as submodule', 'rating as option1', 'favorite', 'crc32', 'Rated_Out_Power as watts')
            );
        }
        foreach($custom_inverters as $inverter){
            $inverter['option2'] = 'w';
            $inverter['custom'] = true;
            $pv_inverters[] = $inverter;
        }
        usort($pv_inverters, function($a, $b) {
            if(strcasecmp($a['module'], $b['module']) < 0) return -1;
            if(strcasecmp($a['module'], $b['module']) > 0) return 1;
            return strcasecmp($a['submodule'], $b['submodule']);
        });

        return json_encode($pv_inverters);
    }

    /**
     * Return the list of stanchions.
     *
     * @return JSON
     */
    public function getStanchions(Request $request){
        $stanchions = Stanchion::all();
        $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 3)->first();
        if(!$favorite)
            $favorite_ids = '';
        else
            $favorite_ids = $favorite->crc32_ids;
        $favorites = explode(",", $favorite_ids);
        foreach($stanchions as $stanchion){
            if(in_array(strval($stanchion['crc32']), $favorites))
                $stanchion['favorite'] = true;
        }
        $stanchions = $stanchions->toArray();

        //Custom
        if(Auth::user()->userrole != 2){
            $custom_stanchions = CustomStanchion::where('client_no', Auth::user()->companyid)->get(
                array('mfr as module', 'model as submodule', 'weight as option1', 'favorite', 'crc32')
            );
        } else {
            $custom_stanchions = CustomStanchion::get(
                array('mfr as module', 'model as submodule', 'weight as option1', 'favorite', 'crc32')
            );
        }
        foreach($custom_stanchions as $stanchion){
            $stanchion['option2'] = 'lb';
            $stanchion['custom'] = true;
            $stanchions[] = $stanchion;
        }
        usort($stanchions, function($a, $b) {
            if(strcasecmp($a['module'], $b['module']) < 0) return -1;
            if(strcasecmp($a['module'], $b['module']) > 0) return 1;
            return strcasecmp($a['submodule'], $b['submodule']);
        });

        return json_encode($stanchions);
    }

    /**
     * Return the list of railsupports.
     *
     * @return JSON
     */
    public function getRailsupport(Request $request){
        $railsupport = RailSupport::all();
        $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 2)->first();
        if(!$favorite)
            $favorite_ids = '';
        else
            $favorite_ids = $favorite->crc32_ids;
        $favorites = explode(",", $favorite_ids);
        foreach($railsupport as $support){
            if(in_array(strval($support['crc32']), $favorites))
                $support['favorite'] = true;
        }
        $railsupport = $railsupport->toArray();

        //Custom
        if(Auth::user()->userrole != 2){
            $custom_supports = CustomRacking::where('client_no', Auth::user()->companyid)->get(
                array('mfr as module', 'model as submodule', 'rack_weight as option1', 'favorite', 'crc32')
            );
        } else {
            $custom_supports = CustomRacking::get(
                array('mfr as module', 'model as submodule', 'rack_weight as option1', 'favorite', 'crc32')
            );
        }
        foreach($custom_supports as $support){
            $support['option2'] = 'lb';
            $support['custom'] = true;
            $railsupport[] = $support;
        }
        usort($railsupport, function($a, $b) {
            if(strcasecmp($a['module'], $b['module']) < 0) return -1;
            if(strcasecmp($a['module'], $b['module']) > 0) return 1;
            return strcasecmp($a['submodule'], $b['submodule']);
        });

        return json_encode($railsupport);
    }

    /**
     * Return the json contents of project.
     *
     * @return JSON
     */
    public function getProjectJson(Request $request){
        if($request['projectId'])
        {
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project)
            {
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->companyid == $project['companyId'])
                {
                    $company = Company::where('id', $project->companyId)->first();
                    $companyNumber = $company ? $company['company_number'] : 0;
                    $folderPrefix = "/" . $companyNumber. '. ' . $project['companyName'] . '/';
                    if( Storage::disk('input')->exists($folderPrefix . $project['requestFile']) )
                        return response()->json(['success' => true, 'data' => Storage::disk('input')->get($folderPrefix . $project['requestFile'])]);
                    else {
                        try{
                            $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                            $dropbox = new Dropbox($app);
                            $dropbox->download(env('DROPBOX_JSON_INPUT') . $companyNumber. '. ' . $project['companyName'] . '/' . $project['requestFile'], storage_path('input') . $folderPrefix . $project['requestFile']);
                            return response()->json(['success' => true, 'data' => Storage::disk('input')->get($folderPrefix . $project['requestFile'])]);
                        } catch(DropboxClientException $e) {
                            return response()->json(['success' => false, 'message' => "Cannot find the project file."] );
                        }
                    }
                }
                else
                {
                    return response()->json(['success' => false, 'message' => "You don't have any permission to view this project."] );
                }
            }
            else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong Project Id.'] );
    }

    /**
     * Return the json contents of project permit.
     *
     * @return JSON
     */
    public function getProjectPermitJson(Request $request){
        if($request['projectId'] && $request['state'])
        {
            $datas = JobPermit::where('job_id', '=', $request['projectId'])->get();
            if($datas)
            {
                // if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3)
                // {
                $fileinfos = array();
                $i = 0;
                foreach($datas as $data){
                    $permit = PermitFiles::where('state', $request['state'])->where('filename', $data['filename'])->first();
                    if($permit)
                        $fileinfos[] = $permit;
                    else
                        $fileinfos[] = array('id' => $i, 'tabname' => $data['filename']);
                    $i ++;
                }
                return response()->json(['success' => true, 'data' => $datas, 'fileinfos' => $fileinfos] );
                // }
                // else
                // {
                //     return response()->json(['success' => false, 'message' => "You don't have any permission to view this project."] );
                // }
            }
            else
                return response()->json(['success' => true, 'message' => 'Cannot find the project.'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong Project Id.'] );
    }

    /**
     * Return the data check contents of project.
     *
     * @return JSON
     */
    public function getDataCheck(Request $request){
        if($request['projectId'])
        {
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project)
            {
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->companyid == $project['companyId'])
                {
                    $check = DataCheck::where('jobId', $request['projectId'])->first();
                    
                    if($check){
                        $datacheck = $check->toArray();
                        $datacheck['notes'] = array();
                        if(strlen($datacheck['structural_notes']) == 1){
                            $note = StructuralNotes::where('id', $datacheck['structural_notes'])->first();
                            if($note)
                                $datacheck['notes'] = $note->note;
                            else
                                $datacheck['notes'] = '';
                        } else {
                            for($i = 0; $i < strlen($datacheck['structural_notes']) - 1; $i += 2){
                                $noteId = substr($datacheck['structural_notes'], $i, 2);
                                $note = StructuralNotes::where('id', $noteId)->first();
                                if($note)
                                    $datacheck['notes'][] = $note->note;
                                else
                                    $datacheck['notes'][] = '';
                            }
                        }
                        return response()->json(['success' => true, 'data' => $datacheck]);
                    }
                    else
                        return response()->json(['success' => false, 'message' => "No Data Check"]);
                }
                else
                    return response()->json(['success' => false, 'message' => "You don't have any permission to view this project."] );
            }
            else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong Project Id.'] );
    }

    /**
     * Delete Project
     *
     * @return JSON
     */
    function delProject(Request $request){
        $id = $request->input('data');
        $res = JobRequest::where('id', $id)->delete();
        return $res;
    }

    /**
     * Set the project state of the project.
     *
     * @return JSON
     */
    public function setProjectState(Request $request){
        if($request['projectId'])
        {
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project)
            {
                if(Auth::user()->userrole == 2 || (Auth::user()->companyid == $project['companyId'] && intval($request['state']) <= 2))
                {
                    if( isset($request['state']) )
                    {
                        $project->projectState = $request['state'];

                        if($project->projectState == 2 || $project->projectState == 4){
                            $project->planCheck = 0;
                            $project->asBuilt = 0;
                            $project->PIL_status = 0;
                        }

                        $project->save();

                        $projectState = JobProjectStatus::where('id', $request['state'])->first();
                        return response()->json(['success' => true, 'stateText' => $projectState->notes, 'stateColor' => $projectState->color]);
                    }
                    else
                        return response()->json(['success' => false, 'message' => "Wrong state value."] );
                }
                else
                    return response()->json(['success' => false, 'message' => "You don't have any permission to set state of this project."] );
            }
            else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong Project Id.'] );
    }

    /**
     * Get the project state of the project.
     *
     * @return JSON
     */
    public function getProjectState(Request $request){
        if($request['jobId'])
        {
            $project = JobRequest::where('id', '=', $request['jobId'])->first();
            if($project)
            {
                if(Auth::user()->userrole == 2 || Auth::user()->companyid == $project['companyId'])
                    return response()->json(['success' => true, 'state' => $project->projectState] );
                else
                    return response()->json(['success' => false, 'message' => "You don't have any permission to set state of this project."] );
            }
            else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong Project Id.'] );
    }

    /**
     * Set the eSeal of the project.
     *
     * @return JSON
     */
    public function setESeal(Request $request){
        if($request['projectId'])
        {
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project)
            {
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || (Auth::user()->companyid == $project['companyId'] && intval($request['state']) <= 2))
                {
                    if($request['planCheck'] == 1){
                        $project->eSeal = 1;
                        $project->reviewerId = Auth::user()->id;
                    }
                    if($request['asBuilt'] == 1){
                        $project->eSeal_asbuilt = 1;
                        $project->reviewerAsbId = Auth::user()->id;
                    }
                    if($request['PIL'] == 1){
                        $project->eSeal_PIL = 1;
                        $project->reviewerPIL = Auth::user()->id;
                    }
                    $project->planCheck = 0;
                    $project->asBuilt = 0;
                    $project->PIL_status = 0;
                    $project->chatIcon = 2;
                    $project->save();
                    JobChat::create([
                        'jobId' => $request['projectId'],
                        'userId' => Auth::user()->id,
                        'text' => "Document review completed. Awaiting sealing and uploading."
                    ]);

                    $users = User::where('usernumber', $project->userId)->where('companyid', $project->companyId)->get();
                        
                    $data = ['projectName' => $project->clientProjectName, 'projectNumber' => $project->clientProjectNumber, 
                    'state' => $project->state];

                    foreach($users as $user){
                        if ($user) {
                            $info = array('to' => $user->email, 'subject' => "Updated iRoof chat for project {$project->clientProjectNumber}. {$project->clientProjectName} {$project->state}");
                            Mail::send('mail.chatnotification', $data, function ($m) use ($info) {
                                $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($info['to'])->subject($info['subject']);
                            });
                        }
                    }

                    return response()->json(['success' => true]);   
                }
                else
                    return response()->json(['success' => false, 'message' => "You don't have any permission to set state of this project."] );
            }
            else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong Project Id.'] );
    }

    /**
     * Set the plan status of the project.
     *
     * @return JSON
     */
    public function setPlanStatus(Request $request){
        if($request['projectId'])
        {
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project)
            {
                if(Auth::user()->userrole == 2)
                {
                    if( isset($request['status']) )
                    {
                        $project->planStatus = $request['status'];
                        $project->save();

                        $planStatus = JobPlanStatus::where('id', $request['status'])->first();
                        return response()->json(['success' => true, 'statusText' => $planStatus->notes, 'statusColor' => $planStatus->color]);
                    }
                    else
                        return response()->json(['success' => false, 'message' => "Wrong status value."] );
                }
                else
                    return response()->json(['success' => false, 'message' => "You don't have any permission to set status of this project."] );
            }
            else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong Project Id.'] );
    }
    
    /**
     * Upload the files of the project to GoDaddy.
     *
     * @return JSON
     */
    public function jobFileUpload(Request $request) {
        if(!empty($request->input('uploadProjectId')) && !empty($request->file('upl')))
        {
            $job = JobRequest::where('id', $request->input('uploadProjectId'))->first();
            if($job){
                $company = Company::where('id', $job['companyId'])->first();
                $companyNumber = $company ? $company['company_number'] : 0;
                $folderPrefix = '/' . $companyNumber. '. ' . $job['companyName'] . '/';
                $file = $request->file('upl');
                
                // $state = '';
                // if(Storage::disk('input')->exists($folderPrefix . $job['requestFile']))
                // {
                //     $jobData = json_decode(Storage::disk('input')->get($folderPrefix . $job['requestFile']), true);
                //     $state = $jobData['ProjectInfo']['State'];
                // }
                $filename = str_replace(array(":",";", "#", "&", "@", "/", "'"), array(""), $file->getClientOriginalName());

                $filepath = $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'];
                $file->move(storage_path('upload') . $filepath, $filename);
                $localpath = storage_path('upload') . $filepath . '/' . $filename;

                $job->planStatus = 1;
                $job->save();
                    
                // $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                // $dropbox = new Dropbox($app);
                // $dropboxFile = new DropboxFile($localpath);
                // $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath . '/' . $file->getClientOriginalName(), ['autorename' => TRUE]);
                
                return response()->json(['success' => true, 'message' => 'Uploaded Successfully', 'filename' => $filename, 'path' => $filepath]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find the project.']);
            }
        }
        else
            return response()->json(['success' => false, 'message' => 'Empty Id or file.']);
    }

    /**
     * Upload the files of the project to Dropbox.
     *
     * @return JSON
     */
    public function jobFilePush(Request $request) {
        if(!empty($request->input('path')) && !empty($request->input('filename')))
        {            
            $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
            $dropbox = new Dropbox($app);
            $dropboxFile = new DropboxFile(storage_path('upload') . $request->input('path') . '/' . $request->input('filename'));
            $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $request->input('path') . '/' . $request->input('filename'), ['autorename' => TRUE]);
            
            return response()->json(['success' => true, 'message' => 'Uploaded Successfully', 'filename' => $request->input('filename'), 'id' => $dropfile->getId(), 'path' => env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $request->input('path') . '/' . $request->input('filename')]);
        }
        else
            return response()->json(['success' => false, 'message' => 'Empty file.']);
    }

    /**
     * Get the filelist from the dropbox.
     *
     * @return JSON
     */
    public function getFileList(Request $request) {
        if(!empty($request->input('projectId')))
        {
            $filelist = array();
            $job = JobRequest::where('id', $request['projectId'])->first();
            if($job){
                $company = Company::where('id', $job['companyId'])->first();
                $companyNumber = $company ? $company['company_number'] : 0;
                $folderPrefix = '/' . $companyNumber . ". " . $job['companyName'] . '/';

                // $state = '';
                // if(Storage::disk('input')->exists($folderPrefix . $job['requestFile']))
                // {
                //     $jobData = json_decode(Storage::disk('input')->get($folderPrefix . $job['requestFile']), true);
                //     $state = $jobData['ProjectInfo']['State'];
                // }
                
                $filepath = $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'];
                    
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                try{
                    $filelist['IN'] = $this->iterateFolder($dropbox, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath . '/', 'IN', 'IN');
                } catch (DropboxClientException $e) { 
                    $filelist['IN'] = array();
                }

                try{
                    $filelist['OUT'] = $this->iterateFolder($dropbox, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_OUT') . $filepath . '/', 'OUT', 'OUT');
                } catch (DropboxClientException $e) {
                    $filelist['OUT'] = array();
                 }

                 return response()->json(['success' => true, 'data' => $filelist, 'directory' => $filepath . '/']);
            } else 
                return response()->json(['success' => false, 'message' => 'Cannot find specified project.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Empty Job Id.']);
    }

    /**
     * Iterate all the subdirectories from the dropbox.
     *
     * @return Object
     */
    public function iterateFolder(Dropbox $dropbox, String $folderPath, String $folderName, String $folderId){
        $content = array('childs' => array(), 'name' => $folderName, 'id' => $folderId, 'type' => 'folder', 'path' => $folderPath);
        $listFolderContents = $dropbox->listFolder($folderPath);
        $files = $listFolderContents->getItems()->all();
        foreach($files as $file){
            if($file->getDataProperty('.tag') === 'file')
                $content['childs'][] = array('name' => $file->getName(), 'id' => $file->getId(), 'type' => 'file', 'path' => $folderPath . $file->getName());
            else
                $content['childs'][] = $this->iterateFolder($dropbox, $folderPath . $file->getName() . '/', $file->getName(), $file->getId());
        }

        return $content;
    }

    /**
     * Delete the file from the dropbox.
     *
     * @return JSON
     */
    public function delDropboxFile(Request $request) {
        if(!empty($request->input('projectId')) && !empty($request->input('filename')))
        {
            $job = JobRequest::where('id', $request->input('projectId'))->first();
            if($job){
                $company = Company::where('id', $job['companyId'])->first();
                $companyNumber = $company ? $company['company_number'] : 0;
                $folderPrefix = '/' . $companyNumber . ". " . $job['companyName'] . '/';

                // $state = '';
                // if(Storage::disk('input')->exists($folderPrefix . $job['requestFile']))
                // {
                //     $jobData = json_decode(Storage::disk('input')->get($folderPrefix . $job['requestFile']), true);
                //     $state = $jobData['ProjectInfo']['State'];
                // }
                
                $filepath = env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'] . '/' . $request->input('filename');
                    
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                try {
                    $dropbox->delete($filepath);
                    return response()->json(['success' => true, 'message' => 'File Deleted Successfully']);
                }
                catch (DropboxClientException $e) {
                    return response()->json(['success' => false, 'message' => 'Cannot find specified file.']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find the project.']);
            }
        }
        else
            return response()->json(['success' => false, 'message' => 'Empty Id or file.']);
    }

    /**
     * Get the temporary links from the dropbox.
     *
     * @return JSON
     */
    public function getDownloadLink(Request $request) {
        if(!empty($request->input('projectId')) && !empty($request->input('files')))
        {
            $job = JobRequest::where('id', $request->input('projectId'))->first();
            if($job){
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                
                if(count($request->input('files')) == 1){
                    try {
                        $temporaryLink = $dropbox->getTemporaryLink($request->input('files')[0]);
                        $filepath = explode('/', $request->input('files')[0]);
                        return response()->json(['success' => true, 'link' => $temporaryLink->getLink(), 'name' => end($filepath)]);
                    }
                    catch (DropboxClientException $e) {
                        return response()->json(['success' => false, 'message' => 'Error while generating dropbox link.']);
                     }
                }
                else if(count($request->input('files')) > 1) {
                    $company = Company::where('id', $job['companyId'])->first();
                    $companyNumber = $company ? $company['company_number'] : 0;
                    $folderPrefix = '/' . $companyNumber . ". " . $job['companyName'] . '/';

                    // $state = '';
                    // if(Storage::disk('input')->exists($folderPrefix . $job['requestFile']))
                    // {
                    //     $jobData = json_decode(Storage::disk('input')->get($folderPrefix . $job['requestFile']), true);
                    //     $state = $jobData['ProjectInfo']['State'];
                    // }

                    $setting = UserSetting::where('userId', Auth::user()->id)->first();
                    
                    
                    $zip = new ZipArchive();
                    $filename = $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'] . ".zip";

                    if(file_exists(storage_path('download') . '/' . $filename))
                        unlink(storage_path('download') . '/' . $filename);

                    $zip->open(storage_path('download') . '/' . $filename, ZipArchive::CREATE);
                    foreach($request->input('files') as $filepath){
                        try {
                            $file = $dropbox->download($filepath);
                            //$path = str_contains($filepath, env('DROPBOX_PREFIX_IN')) ? substr($filepath, strpos($filepath, env('DROPBOX_PREFIX_IN')) + 1) : substr($filepath, strpos($filepath, env('DROPBOX_PREFIX_OUT')) + 1);
                            $path = str_contains($filepath, env('DROPBOX_PREFIX_IN')) ? env('DROPBOX_PREFIX_IN') . '/' . basename($filepath) : env('DROPBOX_PREFIX_OUT') . '/' . basename($filepath);
                            if($setting)
                                $path = $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'] . $path;
                            $zip->addFromString($path, $file->getContents());
                        }
                        catch (DropboxClientException $e){}
                    }
                    $zip->close();
                    //return response()->download(storage_path('download') . '/' . $filename, null, ['Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0']);
                    return response()->json(['success' => true, 'link' => 'downloadZip?filename=' . $filename, 'name' => $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'] . ".zip"]);
                }
            } else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Empty Id or file.']);
    }

    /**
     * Download Project Zip File
     *
     * @return JSON
     */
    public function downloadZip(Request $request){
        if(isset($request['filename'])){
            if( file_exists(storage_path('download') . '/' . $request['filename']))
                return response()->download(storage_path('download') . '/' . $request['filename'], null, ['Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0']);
            else
                return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Cannot find file.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong parameter.']);
    }

    /**
     * Rename dropbox file.
     *
     * @return JSON
     */
    public function renameFile(Request $request) {
        if(!empty($request->input('projectId')) && !empty($request->input('filename')) && !empty($request->input('newname')))
        {
            $job = JobRequest::where('id', $request->input('projectId'))->first();
            if($job){
                $company = Company::where('id', $job['companyId'])->first();
                $companyNumber = $company ? $company['company_number'] : 0;
                $folderPrefix = '/' . $companyNumber . ". " . $job['companyName'] . '/';

                // $state = '';
                // if(Storage::disk('input')->exists($folderPrefix . $job['requestFile']))
                // {
                //     $jobData = json_decode(Storage::disk('input')->get($folderPrefix . $job['requestFile']), true);
                //     $state = $jobData['ProjectInfo']['State'];
                // }
                
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                
                $filepath = env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'] . '/' ;
                $dropbox->move($filepath . $request->input('filename'), $filepath . $request->input('newname'));
                return response()->json(['success' => true]);
            } else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Empty Id or filename params.']);
    }

    /**
     * Return ASCE Options according to the state abbreviation.
     *
     * @return JSON
     */
    public function getASCEOptions(Request $request){
        if(!empty($request['state'])){
            $year_value = ASCEYear::where('jurisdiction_abbrev', $request['state'])->first();
            if($year_value){
                $data = array();
                
                $crc32_roofs = ASCERoofTypes::where('asce', $year_value['asce7_in_years'])->where('rack_crc32', $request['crc32'])->get(); 
                if(count($crc32_roofs) > 0){
                    foreach($crc32_roofs as $type)
                        $data[] = $type->roof_type;
                } else {
                    $roof_types = ASCERoofTypes::where('asce', $year_value['asce7_in_years'])->whereNull('rack_crc32')->get();
                    foreach($roof_types as $type)
                        $data[] = $type->roof_type;
                }
                return response()->json(['success' => true, 'data' => $data]);
            } else
                return response()->json(['success' => false, 'message' => 'Cannot find state']);
        } else
            return response()->json(['success' => false, 'message' => 'Empty State Abbreviation.']);
    }

    /**
     * Return project number duplicate check, max project id.
     *
     * @return JSON
     */
    public function getProjectNumComment(Request $request){
        if(!empty($request['projectNumber']) && !empty($request['projectId'])){
            $self = JobRequest::where('id', $request['projectId'])->first();
            if($self)
                $companyId = $self->companyId;
            else
                $companyId = Auth::user()->companyid;

            $company = Company::where('id', $companyId)->first();
            if($company)
                $max_allowable_skip = $company->max_allowable_skip;
            else
                $max_allowable_skip = 10;

            $job = JobRequest::where('clientProjectNumber', $request['projectNumber'])->where('id', '!=', $request['projectId'])->where('companyId', $companyId)->first();

            $duplicated = false;
            if($job)
                $duplicated = true;
            $maxProject = DB::select(DB::raw('select max(convert(clientProjectNumber, signed integer)) as maxNumber from job_request where companyId=' . $companyId))[0];
            if($maxProject)
                $max = $maxProject->maxNumber;
            else
                $max = 0;
            
            $biggerthanmax = false;
            $maxrange = 0;
            if($request['projectNumber'] > $max){
                //$users = User::where('companyid', Auth::user()->companyid)->get();
                if($request['projectNumber'] > $max + $max_allowable_skip){
                    $biggerthanmax = true;
                    $maxrange = $max + $max_allowable_skip;
                }
            }
            return response()->json(['success' => true, 'duplicated' => $duplicated, 'maxId' => $max, 'biggerthanmax' => $biggerthanmax, 'maxrange' => $maxrange + 1]);
        } else
            return response()->json(['success' => false, 'message' => 'Empty Project Number or Id.']);
    }

    // /**
    //  * Return elapsed time between now and timestamp.
    //  *
    //  * @return JSON
    //  */
    // protected function time_elapsed_string($timestamp, $full = false) {
    //     $now = new DateTime;
    //     $ago = new DateTime;
    //     $ago->setTimestamp(intval($timestamp));
    //     $diff = $now->diff($ago);
    
    //     $diff->w = floor($diff->d / 7);
    //     $diff->d -= $diff->w * 7;
    
    //     $string = array(
    //         'y' => 'year',
    //         'm' => 'month',
    //         'w' => 'week',
    //         'd' => 'day',
    //         'h' => 'hour',
    //         'i' => 'minute',
    //         's' => 'second',
    //     );
    //     foreach ($string as $k => &$v) {
    //         if ($diff->$k) {
    //             $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    //         } else {
    //             unset($string[$k]);
    //         }
    //     }
    
    //     if (!$full) $string = array_slice($string, 0, 1);
    //     return $string ? implode(', ', $string) . ' ago' : 'just now';
    // }

    /**
     * Return project job chat details.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function jobChat(Request $request){
        if(!empty($request['projectId'])){
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || $project->companyId == Auth::user()->companyid)
                {
                    $messages = JobChat::leftjoin('users', "users.id", "=", "job_chat.userId")
                                ->where('job_chat.jobId', $request['projectId'])
                                ->orderBy('job_chat.id', 'desc')
                                ->get(array('job_chat.id as id', 'users.username as username', 'users.userrole as userrole', 'job_chat.text as text', 'job_chat.datetime as datetime'));
                    return view('rsinput.chat')->with('messages', $messages)->with('project', $project)->with('projectId', $request['projectId'])
                    ->with('userrole', Auth::user()->userrole);
                } else
                    return redirect('projectlist');
            } else
                return redirect('projectlist');    
        } else 
            return redirect('projectlist');
    }

    /**
     * Online-review page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function onReview(Request $request){
        if(!empty($request['projectId'])){
            $job = JobRequest::where('id', '=', $request['projectId'])->first();
            if($job){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
                {
                    $company = Company::where('id', $job['companyId'])->first();
                    $companyNumber = $company ? $company['company_number'] : 0;
                    $folderPrefix = '/' . $companyNumber. '. ' . $job['companyName'] . '/';
                    $filepath = $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'];

                    $messages = JobChat::leftjoin('users', "users.id", "=", "job_chat.userId")
                                ->where('job_chat.jobId', $request['projectId'])
                                ->orderBy('job_chat.id', 'desc')
                                ->get(array('job_chat.id as id', 'users.username as username', 'users.userrole as userrole', 'job_chat.text as text', 'job_chat.datetime as datetime'));
                    return view('admin.onreview.view')->with('messages', $messages)->with('job', $job)->with('projectId', $request['projectId'])->with('projecttitle', $job['companyName'] . ' - ' . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state']);
                } else
                    return redirect('projectlist');
            } else
                return redirect('projectlist');    
        } else 
            return redirect('projectlist');
    }

    /**
     * Render report files and IN files list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function jobFiles(Request $request){
        if(!empty($request['projectId'])){
            $job = JobRequest::where('id', '=', $request['projectId'])->first();
            if($job){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
                {
                    $jsonname = 'iRoofTM by Princeton Engineering ' . str_replace(".json", "", $job['requestFile']) . "-";
                    $filename = $jsonname . $job['clientProjectNumber'] . '.' . $job['clientProjectName'] . ' ' . $job['state'];

                    $reportfiles = array();
                    $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                    $dropbox = new Dropbox($app);
                    try {
                        $meta = $dropbox->getMetaData(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . ".pdf");
                        if($meta){
                            if(!Storage::disk('report')->exists($filename . ".pdf") || Storage::disk('report')->size($filename . ".pdf") != $meta->getSize()){
                                $dropbox->download(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . ".pdf", storage_path('report') . '/' . $filename . ".pdf");
                            }
                            array_push($reportfiles, array("filename" => $filename . ".pdf", "size" => $meta->getSize(), "modifiedDate" => $meta->getServerModified(), "link" => env('APP_URL') . "report/" . $filename . ".pdf"));
                        }
                    } catch (DropboxClientException $e) { }
                    try {
                        $meta = $dropbox->getMetaData(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " s.pdf");
                        if($meta){
                            if(!Storage::disk('report')->exists($filename . " s.pdf") || Storage::disk('report')->size($filename . " s.pdf") != $meta->getSize()){
                                $dropbox->download(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " s.pdf", storage_path('report') . '/' . $filename . " s.pdf");
                            }
                            array_push($reportfiles, array("filename" => $filename . " s.pdf", "size" => $meta->getSize(), "modifiedDate" => $meta->getServerModified(), "link" => env('APP_URL') . "report/" . $filename . " s.pdf"));
                        }
                    } catch (DropboxClientException $e) { }
                    try {
                        $meta = $dropbox->getMetaData(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " binder s.pdf");
                        if($meta){
                            if(!Storage::disk('report')->exists($filename . " binder s.pdf") || Storage::disk('report')->size($filename . " binder s.pdf") != $meta->getSize()){
                                $dropbox->download(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " binder s.pdf", storage_path('report') . '/' . $filename . " binder s.pdf");
                            }
                            array_push($reportfiles, array("filename" => $filename . " binder s.pdf", "size" => $meta->getSize(), "modifiedDate" => $meta->getServerModified(), "link" => env('APP_URL') . "report/" . $filename . " binder s.pdf"));
                        }
                    } catch (DropboxClientException $e) { }
                    try {
                        $meta = $dropbox->getMetaData(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " Data Check.pdf");
                        if($meta){
                            if(!Storage::disk('report')->exists($filename . " Data Check.pdf") || Storage::disk('report')->size($filename . " Data Check.pdf") != $meta->getSize()){
                                $dropbox->download(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " Data Check.pdf", storage_path('report') . '/' . $filename . " Data Check.pdf");
                            }
                            array_push($reportfiles, array("filename" => $filename . " Data Check.pdf", "size" => $meta->getSize(), "modifiedDate" => $meta->getServerModified(), "link" => env('APP_URL') . "report/" . $filename . " Data Check.pdf"));
                        }
                    } catch (DropboxClientException $e) { }

                    $company = Company::where('id', $job['companyId'])->first();
                    $companyNumber = $company ? $company['company_number'] : 0;
                    $folderPrefix = '/' . $companyNumber . ". " . $job['companyName'] . '/';
                    
                    $filepath = $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'];
                        
                    $infiles = array();
                    try{
                        $listFolderContents = $dropbox->listFolder(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath . '/');
                        $files = $listFolderContents->getItems()->all();
                        if(!file_exists(storage_path('upload') . $filepath))
                            Storage::disk('upload')->makeDirectory($filepath);

                        foreach($files as $file){
                            if($file->getDataProperty('.tag') === 'file'){
                                if(!file_exists(storage_path('upload') . $filepath . '/' . $file->getName()) || filesize(storage_path('upload') . $filepath . '/' . $file->getName()) != $file->getSize()){
                                    $dropbox->download(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath . '/' . $file->getName(), storage_path('upload') . $filepath . '/' . $file->getName());
                                }
                                $infiles[] = array('filename' => $file->getName(), 'size' => $file->getSize(), 'modifiedDate' => $file->getServerModified(), 'link' => env('APP_URL') . 'in/' . $request['projectId'] . '/' . $file->getName());
                            }
                        }
                    } catch (DropboxClientException $e) { 
                        $infiles = array();
                    }

                    try{
                        $listFolderContents = $dropbox->listFolder(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_INCOPY') . $filepath . '/');
                        $files = $listFolderContents->getItems()->all();
                        foreach($files as $file){
                            if(str_contains($file->getName(), 'PIL_f.pdf')){
                                if(!file_exists(storage_path('upload') . $filepath . '/' . $file->getName()) || filesize(storage_path('upload') . $filepath . '/' . $file->getName()) != $file->getSize()){
                                    $dropbox->download(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_INCOPY') . $filepath . '/' . $file->getName(), storage_path('upload') . $filepath . '/' . $file->getName());
                                }
                                array_push($infiles, array('filename' => $file->getName(), 'size' => $file->getSize(), 'modifiedDate' => $file->getServerModified(), 'link' => env('APP_URL') . 'in/' . $request['projectId'] . '/' . $file->getName()));
                            }
                        }
                    } catch (DropboxClientException $e) { }

                    return view('admin.onreview.filelist')->with('reportfiles', $reportfiles)->with('infiles', $infiles)->with('projecttitle', $job['companyName'] . ' - ' . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state']);
                } else
                    return redirect('home');
            } else
                return redirect('home');  
        } else 
            return redirect('home');
    }

    /**
     * Return report files and Biggest sized IN file.
     *
     * @return JSON
     */
    public function getMainJobFiles(Request $request){
        if(!empty($request['projectId'])){
            $job = JobRequest::where('id', '=', $request['projectId'])->first();
            if($job){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
                {
                    $jsonname = 'iRoofTM by Princeton Engineering ' . str_replace(".json", "", $job['requestFile']) . "-";
                    $filename = $jsonname . $job['clientProjectNumber'] . '.' . $job['clientProjectName'] . ' ' . $job['state'];

                    $reportfiles = array();
                    $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                    $dropbox = new Dropbox($app);
                    try {
                        $meta = $dropbox->getMetaData(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . ".pdf");
                        if($meta){
                            if(!Storage::disk('report')->exists($filename . ".pdf") || Storage::disk('report')->size($filename . ".pdf") != $meta->getSize()){
                                $dropbox->download(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . ".pdf", storage_path('report') . '/' . $filename . ".pdf");
                            }
                            array_push($reportfiles, array("filename" => $filename . ".pdf", "size" => $meta->getSize(), "modifiedDate" => $meta->getServerModified(), "link" => env('APP_URL') . "report/" . $filename . ".pdf"));
                        }
                    } catch (DropboxClientException $e) { }
                    try {
                        $meta = $dropbox->getMetaData(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " s.pdf");
                        if($meta){
                            if(!Storage::disk('report')->exists($filename . " s.pdf") || Storage::disk('report')->size($filename . " s.pdf") != $meta->getSize()){
                                $dropbox->download(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " s.pdf", storage_path('report') . '/' . $filename . " s.pdf");
                            }
                            array_push($reportfiles, array("filename" => $filename . " s.pdf", "size" => $meta->getSize(), "modifiedDate" => $meta->getServerModified(), "link" => env('APP_URL') . "report/" . $filename . " s.pdf"));
                        }
                    } catch (DropboxClientException $e) { }
                    try {
                        $meta = $dropbox->getMetaData(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " binder s.pdf");
                        if($meta){
                            if(!Storage::disk('report')->exists($filename . " binder s.pdf") || Storage::disk('report')->size($filename . " binder s.pdf") != $meta->getSize()){
                                $dropbox->download(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " binder s.pdf", storage_path('report') . '/' . $filename . " binder s.pdf");
                            }
                            array_push($reportfiles, array("filename" => $filename . " binder s.pdf", "size" => $meta->getSize(), "modifiedDate" => $meta->getServerModified(), "link" => env('APP_URL') . "report/" . $filename . " binder s.pdf"));
                        }
                    } catch (DropboxClientException $e) { }
                    try {
                        $meta = $dropbox->getMetaData(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " Data Check.pdf");
                        if($meta){
                            if(!Storage::disk('report')->exists($filename . " Data Check.pdf") || Storage::disk('report')->size($filename . " Data Check.pdf") != $meta->getSize()){
                                $dropbox->download(env('DROPBOX_PROJECTS_PATH') . '/Reports/' . $filename . " Data Check.pdf", storage_path('report') . '/' . $filename . " Data Check.pdf");
                            }
                            array_push($reportfiles, array("filename" => $filename . " Data Check.pdf", "size" => $meta->getSize(), "modifiedDate" => $meta->getServerModified(), "link" => env('APP_URL') . "report/" . $filename . " Data Check.pdf"));
                        }
                    } catch (DropboxClientException $e) { }

                    $company = Company::where('id', $job['companyId'])->first();
                    $companyNumber = $company ? $company['company_number'] : 0;
                    $folderPrefix = '/' . $companyNumber . ". " . $job['companyName'] . '/';
                    
                    $filepath = $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'];
                        
                    $infile = array();
                    $bigsize = 0;
                    try{
                        $listFolderContents = $dropbox->listFolder(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath . '/');
                        $files = $listFolderContents->getItems()->all();
                        foreach($files as $file){
                            if($file->getDataProperty('.tag') === 'file'){
                                if(!file_exists(storage_path('upload') . $filepath . '/' . $file->getName()) || filesize(storage_path('upload') . $filepath . '/' . $file->getName()) != $file->getSize()){
                                    $dropbox->download(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath . '/' . $file->getName(), storage_path('upload') . $filepath . '/' . $file->getName());
                                }
                                if($file->getSize() > $bigsize){
                                    $bigsize = $file->getSize();
                                    $infile = array('filename' => $file->getName(), 'size' => $file->getSize(), 'modifiedDate' => $file->getServerModified(), 'link' => env('APP_URL') . 'in/' . $request['projectId'] . '/' . $file->getName());
                                }
                            }
                        }
                    } catch (DropboxClientException $e) { 
                        $infile = array();
                    }
                    array_push($reportfiles, $infile);

                    try{
                        $listFolderContents = $dropbox->listFolder(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_INCOPY') . $filepath . '/');
                        $files = $listFolderContents->getItems()->all();
                        foreach($files as $file){
                            if(str_contains($file->getName(), 'PIL_f.pdf')){
                                if(!file_exists(storage_path('upload') . $filepath . '/' . $file->getName()) || filesize(storage_path('upload') . $filepath . '/' . $file->getName()) != $file->getSize()){
                                    $dropbox->download(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_INCOPY') . $filepath . '/' . $file->getName(), storage_path('upload') . $filepath . '/' . $file->getName());
                                }
                                array_push($reportfiles, array('filename' => $file->getName(), 'size' => $file->getSize(), 'modifiedDate' => $file->getServerModified(), 'link' => env('APP_URL') . 'in/' . $request['projectId'] . '/' . $file->getName()));
                            }
                        }
                    } catch (DropboxClientException $e) { }

                    return response()->json(['success' => true, 'files' => $reportfiles]);
                } else
                    return response()->json(['success' => false, 'message' => 'No permission.']);
            } else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.']);
        } else 
            return response()->json(['success' => false, 'message' => 'Empty projectId.']);
    }

    /**
     * Return the file contents of report.
     *
     * @return FILE or Redirect
     */
    public function getReport($name){
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4){
            if(Storage::disk('report')->exists($name)){
                $path = storage_path('report') . '/' . $name;
                
                $file = Storage::disk('report')->get($name);
                $type = Storage::disk('report')->mimeType($name);

                $response = Response::make($file, 200);
                $response->header("Content-Type", $type);

                return $response;
            } else
                abort(404);
        } else
            return redirect('home');
    }

    /**
     * Check Dropbox IN folder to return IN files' list.
     *
     * @return JSON
     */
    public function getInDIRList(Request $request){
        if(!empty($request['projectId'])){
            $job = JobRequest::where('id', '=', $request['projectId'])->first();
            if($job){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
                {
                    $company = Company::where('id', $job['companyId'])->first();
                    $companyNumber = $company ? $company['company_number'] : 0;
                    $folderPrefix = '/' . $companyNumber . ". " . $job['companyName'] . '/';
                    
                    $filepath = $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'];
                        
                    $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                    $dropbox = new Dropbox($app);
                    $infiles = array();
                    try{
                        $listFolderContents = $dropbox->listFolder(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath . '/');
                        $files = $listFolderContents->getItems()->all();
                        foreach($files as $file){
                            if($file->getDataProperty('.tag') === 'file'){
                                if(!file_exists(storage_path('upload') . $filepath . '/' . $file->getName()) || filesize(storage_path('upload') . $filepath . '/' . $file->getName()) != $file->getSize()){
                                    $dropbox->download(env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath . '/' . $file->getName(), storage_path('upload') . $filepath . '/' . $file->getName());
                                }
                                $infiles[] = array('filename' => $file->getName(), 'size' => $file->getSize(), 'modifiedDate' => $file->getServerModified(), 'link' => env('APP_URL') . 'in/' . $request['projectId'] . '/' . $file->getName());
                            }
                        }
                    } catch (DropboxClientException $e) { 
                        $infiles = array();
                    }

                    return response()->json(["files" => $infiles, "success" => true]);
                } else
                    return response()->json(["message" => "You don't have permission.", "success" => false]);
            } else
                return response()->json(["message" => "Cannot find project.", "success" => false]);  
        } else 
            return response()->json(["message" => "Empty project id.", "success" => false]);
    }

    /**
     * Return the file contents of report.
     *
     * @return FILE or Redirect
     */
    public function getINFile($jobId, $filename){
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4){
            $job = JobRequest::where('id', '=', $jobId)->first();
            if($job){
                $company = Company::where('id', $job['companyId'])->first();
                $companyNumber = $company ? $company['company_number'] : 0;
                $folderPrefix = '/' . $companyNumber . ". " . $job['companyName'] . '/';

                $filepath = $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $job['state'];
                        
                if(file_exists(storage_path('upload') . $filepath . '/' . $filename)){
                    $path = storage_path('upload') . $filepath . '/' . $filename;
                    
                    $file = Storage::disk('upload')->get($filepath . '/' . $filename);
                    $type = Storage::disk('upload')->mimeType($filepath . '/' . $filename);
    
                    $response = Response::make($file, 200);
                    $response->header("Content-Type", $type);
    
                    return $response;
                } else
                    abort(404);   
            }
        } else
            return redirect('home');
    }

    /**
     * Add chat message to job_chat table.
     *
     * @return JSON
     */
    public function submitChat(Request $request){
        $project = JobRequest::where('id', '=', $request['projectId'])->first();
        if($project){
            if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || $project->companyId == Auth::user()->companyid)
            {
                $chatItem = JobChat::create([
                    'jobId' => $request['projectId'],
                    'userId' => Auth::user()->id,
                    'text' => $request['message']
                ]);
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4){
                    $users = User::where('usernumber', $project->userId)->where('companyid', $project->companyId)->get();
                        
                    // $state = JobProjectStatus::where('id', $project->state)->first();
                    //Mailing
                    $data = ['projectName' => $project->clientProjectName, 'projectNumber' => $project->clientProjectNumber, 
                    'state' => $project->state];

                    foreach($users as $user){
                        if ($user) {
                            $info = array('to' => $user->email, 'subject' => "Updated iRoof chat for project {$project->clientProjectNumber}. {$project->clientProjectName} {$project->state}");
                            Mail::send('mail.chatnotification', $data, function ($m) use ($info) {
                                $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($info['to'])->subject($info['subject']);
                            });
                        }
                    }

                    if(Auth::user()->userrole == 4)
                        $project->chatIcon = 3;
                    else
                        $project->chatIcon = 2;
                }
                else
                    $project->chatIcon = 1;
                $project->save();

                $chatId = $chatItem->id;
                return response()->json(["status" => true, "user" => Auth::user()->username, "role" => Auth::user()->userrole, "message" => $request["message"], "datetime" => date('Y-m-d H:i:s', strtotime("-5 hours")), "id" => $chatId]);
            }
            else
                return response()->json(["message" => "You don't have permission to edit this project.", "status" => false]);
        }
        else
            return response()->json(["message" => "Cannot find project.", "status" => false]);
    }
    /**
     * Update the chat history of the project.
     *
     * @return JSON
     */
    public function updateChat(Request $request){
        if($request['chatId'])
        {
            $chatItem = JobChat::where('id', '=', $request['chatId'])->first();
            if($chatItem)
            {
                if(Auth::user()->userrole == 2)
                {
                    if( isset($request['text']) )
                    {
                        $chatItem->text = $request['text'];
                        $chatItem->save();

                        $project = JobRequest::where('id', '=', $request['projectId'])->first();
                        $users = User::where('usernumber', $project->userId)->where('companyid', $project->companyId)->get();
                        
                        //$state = JobProjectStatus::where('id', $project->state)->first();
                        //Mailing
                        $data = ['projectName' => $project->clientProjectName, 'projectNumber' => $project->clientProjectNumber, 
                        'state' => $project->state];

                        foreach($users as $user){
                            if ($user) {
                                $info = array('to' => $user->email, 'subject' => "Updated iRoof chat for project {$project->clientProjectNumber}. {$project->clientProjectName} {$project->state}");
                                Mail::send('mail.chatnotification', $data, function ($m) use ($info) {
                                    $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($info['to'])->subject($info['subject']);
                                });
                            }
                        }
                       
                        return response()->json(['success' => true]);
                    }
                    else
                        return response()->json(['success' => false] );
                }
                else
                    return response()->json(['success' => false, 'message' => "You don't have any permission to set text of this project."] );
            }
            else
                return response()->json(['success' => false, 'message' => 'Cannot find the chat.'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong Chat Id.'] );
    }
    /**
     * Delete Chat
     *
     * @return JSON
     */
    function delChat(Request $request){
        $id = $request->input('data');
        $res = JobChat::where('id', $id)->delete();

        $project = JobRequest::where('id', '=', $request->input('projectId'))->first();
        $users = User::where('usernumber', $project->userId)->where('companyid', $project->companyId)->get();
        
        // $state = JobProjectStatus::where('id', $project->state)->first();
        $data = ['projectName' => $project->clientProjectName, 'projectNumber' => $project->clientProjectNumber, 
        'state' => $project->state];
        
        //Mailing
        foreach($users as $user){
            if ($user) {
                $info = array('to' => $user->email, 'subject' => "Deleted iRoof chat for project {$project->clientProjectNumber}. {$project->clientProjectName} {$project->state}");
                Mail::send('mail.chatnotification', $data, function ($m) use ($info) {
                    $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($info['to'])->subject($info['subject']);
                });
            }
        }
        return $res;
    }
    /**
     * Get method to set previous jobs' state value
     *
     * @return JSON
     */
    // public function setStates(Request $request){
    //     $jobs = JobRequest::get();
    //     foreach($jobs as $job){
    //         $company = Company::where('id', $job['companyId'])->first();
    //         $companyNumber = $company ? $company['company_number'] : 0;
    //         $folderPrefix = '/' . $companyNumber. '. ' . $job['companyName'] . '/';
    //         $file = $request->file('upl');
            
    //         $state = '';
    //         if(Storage::disk('input')->exists($folderPrefix . $job['requestFile']))
    //         {
    //             $jobData = json_decode(Storage::disk('input')->get($folderPrefix . $job['requestFile']), true);
    //             $state = $jobData['ProjectInfo']['State'];
    //         }
    //         $job->state = $state;
    //         $job->save();
    //     }
    //     return response()->json(["message" => "Success!"]);
    // }

    /**
     * Toggle the plan check value of job.
     *
     * @return JSON
     */
    public function togglePlanCheck(Request $request){
        $project = JobRequest::where('id', '=', $request['jobId'])->first();
            if($project){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || $project->companyId == Auth::user()->companyid)
                {
                    if($project->planCheck == 1)
                        $project->planCheck = 0;
                    else
                        $project->planCheck = 1;
                    $project->save();
                    return response()->json(["message" => "Success!", "success" => true]);
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this project.", "success" => false]);
            }
            else
                return response()->json(["message" => "Cannot find project.", "success" => false]);
    }

    /**
     * Toggle the as built value of job.
     *
     * @return JSON
     */
    public function toggleAsBuilt(Request $request){
        $project = JobRequest::where('id', '=', $request['jobId'])->first();
            if($project){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || $project->companyId == Auth::user()->companyid)
                {
                    if($project->asBuilt == 1)
                        $project->asBuilt = 0;
                    else
                        $project->asBuilt = 1;
                    $project->save();
                    return response()->json(["message" => "Success!", "success" => true]);
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this project.", "success" => false]);
            }
            else
                return response()->json(["message" => "Cannot find project.", "success" => false]);
    }

    /**
     * Toggle the as PIL_status value of job.
     *
     * @return JSON
     */
    public function togglePIL(Request $request){
        $project = JobRequest::where('id', '=', $request['jobId'])->first();
            if($project){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || $project->companyId == Auth::user()->companyid)
                {
                    if($project->PIL_status == 1)
                        $project->PIL_status = 0;
                    else{
                        $project->PIL_status = 1;
                        $project->PIL_checkbox_id = Auth::user()->id;
                    }
                    $project->save();
                    return response()->json(["message" => "Success!", "success" => true]);
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this project.", "success" => false]);
            }
            else
                return response()->json(["message" => "Cannot find project.", "success" => false]);
    }

    /**
     * Reset Review checks
     *
     * @return JSON
     */
    public function resetReviewChecks(Request $request){
        $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || $project->companyId == Auth::user()->companyid)
                {
                    $project->asBuilt = 0;
                    $project->planCheck = 0;
                    $project->PIL_status = 0;
                    $project->save();
                    return response()->json(["message" => "Success!", "success" => true]);
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this project.", "success" => false]);
            }
            else
                return response()->json(["message" => "Cannot find project.", "success" => false]);
    }

    /**
     * Check job chat messages count and return updated ones
     *
     * @return JSON
     */
    public function checkChatList(Request $request){
        if(!empty($request['jobId']) && !empty($request['msgCount'])){
            $project = JobRequest::where('id', '=', $request['jobId'])->first();
            if($project){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || $project->companyId == Auth::user()->companyid)
                {
                    $messages = JobChat::leftjoin('users', "users.id", "=", "job_chat.userId")
                            ->where('job_chat.jobId', $request['jobId'])
                            ->orderBy('job_chat.id', 'desc')
                            ->get(array('job_chat.id as id', 'users.username as user', 'users.userrole as role', 'job_chat.text as message', 'job_chat.datetime as datetime'))->toArray();
                    return response()->json(["success" => true, "msgCount" => count($messages), "msgs" => array_slice($messages, 0, count($messages) - $request['msgCount'])]);
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this project.", "success" => false]);
            } else
                return response()->json(["message" => "Cannot find project.", "success" => false]);
        } else 
            return response()->json(["message" => "Wrong Parameters.", "success" => false]);
    }

    /**
     * Return Permit Files List for the state
     *
     * @return JSON
     */
    public function getPermitList(Request $request){
        if(!empty($request['state'])){
            if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->allow_permit > 0){
                $list = PermitFiles::where('state', $request['state'])->where('formtype', '1')->get();
                return response()->json(["data" => $list, "success" => true]);
            } else {
                return response()->json(["message" => "You don't have permission to permit tab.", "success" => false]);
            }
        } else 
            return response()->json(["message" => "Wrong Parameters.", "success" => false]);
    }

    /**
     * Return PIL Files list for a state
     *
     * @return JSON
     */
    public function getPILList(Request $request){
        if(!empty($request['state'])){
            if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->allow_permit > 0){
                $list = PermitFiles::where('state', $request['state'])->where('formtype', '2')->get();
                return response()->json(["data" => $list, "success" => true]);
            } else {
                return response()->json(["message" => "You don't have permission to PIL tab.", "success" => false]);
            }
        } else 
            return response()->json(["message" => "Wrong Parameters.", "success" => false]);
    }

    /**
     * Return Company Info + Company Permit Info
     *
     * @return JSON
     */
    public function getCompanyInfo(Request $request){
        if(!empty($request['state'])){
            $company = Company::where('id', Auth::user()->companyid)->first();
            if($company){
                $companyPermit = PermitInfo::where('state', $request['state'])->where('company_id', Auth::user()->companyid)->first();
                if($companyPermit){
                    return response()->json(["company" => $company, "permit" => $companyPermit, "success" => true]);
                }
                else    
                    return response()->json(["company" => $company, "permit" => $companyPermit, "success" => true]);
                
            } else 
                return response()->json(["message" => "Cannot find the company.", "success" => false]);
        } else 
            return response()->json(["message" => "Wrong Parameters.", "success" => false]);
    }

    /**
     * Set Job ReviewerId and lastReviewTime
     *
     * @return JSON
     */
    public function setReviewer(Request $request){
        if(!empty($request['projectId'])){
            if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4){
                $job = JobRequest::where('id', $request['projectId'])->first();
                if($job){
                    $job->reviewerId = Auth::user()->id;
                    $job->lastReviewTime = gmdate("Y-m-d\TH:i:s", time());
                    $job->save();
                    return response()->json(["success" => true]);
                } else
                    return response()->json(["message" => "Cannot find the project.", "success" => false]);
            } else
                return response()->json(["message" => "You don't have permission.", "success" => false]);
        } else
            return response()->json(["message" => "Missing project id.", "success" => false]);
    }

    /**
     * Check Reviewer
     *
     * @return JSON
     */
    public function checkReviewer(Request $request){
        if(!empty($request['projectId'])){
            if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4){
                $job = JobRequest::where('id', $request['projectId'])->first();
                if($job){
                    if(time() - strtotime($job->lastReviewTime) >= 10)
                        return response()->json(["inReview" => false, "success" => true]);
                    else
                        return response()->json(["inReview" => true, "success" => true]);
                } else
                    return response()->json(["message" => "Cannot find the project.", "success" => false]);
            } else
                return response()->json(["message" => "You don't have permission.", "success" => false]);
        } else
            return response()->json(["message" => "Missing project id.", "success" => false]);
    }

    /**
     * Check Correct Town and return Recommended Town
     *
     * @return JSON
     */
    public function checkCorrectTown(Request $request){
        if(!empty($request['city']) && !empty($request['state'])){
            $town = TownNameLocations::where('state', $request['state'])->where('City_Town', $request['city'])->first();
            if($town)
                return response()->json(["status" => true]);
            
            $towns = TownNameLocations::where('state', $request['state'])->get();
            if(count($towns)){
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?address=".str_replace(" ", "", $request['city']).",{$request['state']}&key=AIzaSyB6zzkSrnFTQ13is6pqEuJNVH4UVE-GUs4"); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                $response = curl_exec($ch);

                // $response = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$request['city']}, {$request['state']}&key=AIzaSyB6zzkSrnFTQ13is6pqEuJNVH4UVE-GUs4");
                $geo = json_decode($response);

                if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200 && $geo->status == "OK"){
                    $lat = $geo->results[0]->geometry->location->lat;
                    $lng = $geo->results[0]->geometry->location->lng;
                    $distance = 99999;
                    $recommended = '';
                    foreach($towns as $town){
                        $dist = $this->distance($lat, $lng, $town->Lat, $town->Lng);
                        if($dist < $distance){
                            $distance = $dist;
                            $recommended = $town->City_Town;
                        }
                    }
                    return response()->json(["status" => false, "recommended" => $recommended, "distance" => $distance]);
                } else 
                    return response()->json(["status" => false, "message" => "Geo API Failed.", "response" => $response]);
            } else
                return response()->json(["status" => false, "message" => "No State Records."]);
        } else 
            return response()->json(["status" => false, "message" => "Empty city or state Param."]);
    }

    /**
     * Return distance between two lat / long
     *
     * @return Double
     */
    private function distance($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles;
      }

      /**
     * Check the user company's unpaid invoices and if they're outdated
     *
     * @return Boolean
     */
      private function checkOutdatedBill(){
        if(Auth::user()->userrole == 2)
            return false;
        
        $billInfo = BillingInfo::where('clientId', Auth::user()->companyid)->first();
        if(!$billInfo || $billInfo->block_on_fail != 1 || $billInfo->block_days_after == 0 || $billInfo->card_number == '')
            return false;
        
        $bills = BillingHistory::where('companyId', Auth::user()->companyid)->where('state', '<=', '1')->get();

        $now = time();
        foreach($bills as $bill){
            $issuedAt = strtotime($bill->issuedAt);
            if($now - $issuedAt > $billInfo->block_days_after * 24 * 60 * 60){
                return true;
            }
        }

        return false;
      }

      /**
     * Return the System Msgs Data
     *
     * @return JSON
     */
      public function getSystemMsgs(){
        $msg = SystemMsgs::first();
        return response()->json($msg);
      }

     /**
     * Return the Sub Clients of project
     *
     * @return JSON
     */
    public function getJobSubClients(Request $request){
        if($request['jobId'] > 0){
            $job = JobRequest::where('id', $request['jobId'])->first();
            if($job){
                $clients = SubClients::where('client_id', $job->companyId)->get(array('id', 'name'));
                return response()->json(['success' => true, 'clients' => $clients]);
            } else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.']);
        } else {
            $clients = SubClients::where('client_id', Auth::user()->companyid)->get(array('id', 'name'));
            return response()->json(['success' => true, 'clients' => $clients]);
        }
    }

    /**
     * Return subclient is allowed
     *
     * @return JSON
     */
    public function isSubClientAllowed(Request $request){
        $company = Company::where('id', Auth::user()->companyid)->first();
        if($company && $company->allow_subclient == 1)
            return response()->json(['success' => true]);
        else
            return response()->json(['success' => false]);
    }

    /**
     * Return subclient is allowed for a job
     *
     * @return JSON
     */
    public function jobSubClientAllowed(Request $request){
        $job = JobRequest::where('id', $request['jobId'])->first();
        if($job){
            $company = Company::where('id', $job->companyId)->first();
            if($company && $company->allow_subclient == 1)
                return response()->json(['success' => true]);
            else
                return response()->json(['success' => false]);
        } else {
            $company = Company::where('id', Auth::user()->companyid)->first();
            if($company && $company->allow_subclient == 1)
                return response()->json(['success' => true]);
            else
                return response()->json(['success' => false]);
        }
    }
}
