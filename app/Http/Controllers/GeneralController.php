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
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;

use DateTime;
use DateTimeZone;
use ZipArchive;
use DB;
use Mail;

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
    public function index()
    {
        if( Auth::user()->userrole == 2 )
            return view('admin.home');
        else if( Auth::user()->userrole == 1 || Auth::user()->userrole == 3)
            return view('clientadmin.home');
        else if( Auth::user()->userrole == 0 )
            return view('user.home');
    }

    /**
     * Show the rf multiple input form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function rsinput(Request $request)
    {
        $company = Company::where('id', Auth::user()->companyid)->first();
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
                    ->with('projectId', $request['projectId'] ? $request['projectId'] : -1)
                    ->with('userId', $project ? $project['userId'] : 0)
                    ->with('offset', $company['offset']);
        }
        else
        {
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            $companymembers = User::where('companyid', $project['companyId'])->get();
            return view('rsinput.body')
                    ->with('companyName', "")
                    ->with('companyNumber', "")
                    ->with('companyMembers', $companymembers)
                    ->with('projectState', 0)
                    ->with('projectId', $request['projectId'] ? $request['projectId'] : -1)
                    ->with('userId', $project ? $project['userId'] : 0)
                    ->with('offset', 0.5);
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
                    
                    if( Storage::disk('input')->exists($folderPrefix . $project['requestFile']) )
                        Storage::disk('input')->delete($folderPrefix . $project['requestFile']);
                    Storage::disk('input')->put($folderPrefix . $project['requestFile'], json_encode($data));
                    
                    // //Backup json file to dropbox
                    $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                    $dropbox = new Dropbox($app);
                    $dropboxFile = new DropboxFile(storage_path('/input/') . $companyNumber. '. ' . $project['companyName'] . '/' . $project['requestFile']);
                    $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_JSON_INPUT') . $companyNumber. '. ' . $project['companyName'] . '/' . $project['requestFile'], ['mode' => 'overwrite']);
                    
                    $projectState = 0;
                    if($request['status'] == 'Saved')
                        $projectState = 1;
                    else if($request['status'] == 'Data Check')
                        $projectState = 2;
                    else if($request['status'] == 'Submitted')
                        $projectState = 4;
                    $project->projectState = $projectState;
                    $project->clientProjectName = $request['data']['txt-project-name'];
                    $project->clientProjectNumber = $request['data']['txt-project-number'];
                    $project->submittedTime = gmdate("Y-m-d\TH:i:s", time());
                    $project->state = $request['data']['option-state'];
                    $project->userId = $request['data']['option-user-id'];
                    $project->save();

                    $company->last_accessed = gmdate("Y-m-d", time());
                    $company->save();

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

                Storage::disk('input')->put($folderPrefix . $filename, json_encode($data));

                //Backup json file to dropbox
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                $dropboxFile = new DropboxFile(storage_path('/input/') . $companyNumber. '. ' . $company['company_name'] . '/' . $filename);
                $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_JSON_INPUT') . $companyNumber. '. ' . $company['company_name'] . '/' . $filename, ['autorename' => TRUE]);

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
            }
            catch(Exception $e) {
                return response()->json(["message" => "Failed to generate RS json data file", "status" => false]);
            }

            $companyNumber = $company ? $company['company_number'] : 0;
            return response()->json(["message" => "Success!", "status" => true, "projectId" => $projectId, "directory" => "/" . $companyNumber. '. ' . $project['companyName'] . '/' . $project['clientProjectNumber'] . '. ' . $project['clientProjectName'] . ' ' . $request['data']['option-state'] . '/']);
        }
    }
/**
     * Save Input Datas as files
     *
     * @return JSON
     */
    public function submitPermitInput(Request $request){
        if($request['data'] && $request['data']['projectId'] && $request['data']['projectId'] > 0){
            $project = JobPermit::where('job_id', '=', $request['data']['projectId'])->where('filename', '=', $request['filename'])->first();
            if($project){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $project->companyId == Auth::user()->companyid)
                {
                    $project->data = json_encode($request['data']);
                    $project->save();
                    return response()->json(["message" => "Success!", "status" => true, "projectId" => $project->id]);
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this project.", "status" => false]);
            }
            else
            {
                $filename = $request['filename'];
                try {
                    $project = JobPermit::create([
                        'job_id' => $request['data']['projectId'],
                        'filename' => $request['filename'],
                        'data'=> json_encode($request['data'])
                    ]);
                }
                catch(Exception $e) {
                    return response()->json(["message" => "Failed to generate Permit json data file", "status" => false]);
                }
                return response()->json(["message" => "Success!", "status" => true, "projectId" => $project->id]);
            }
        }
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
                    $company = Company::where('id', $project->companyId)->first();
                    $companyNumber = $company ? $company['company_number'] : 0;
                    
                    $folderPrefix = '/' . $companyNumber . ". " . $project['companyName'] . '/';
                    $filepath = $folderPrefix . $project['clientProjectNumber'] . '. ' . $project['clientProjectName'] . ' ' . $project['state'];
                    
                    $user = User::where('companyid', $project['companyId'])->where('usernumber', $project['userId'])->first();

                    if( Storage::disk('output')->exists($folderPrefix . $request->filename) ) {
                        Storage::disk('output')->delete($folderPrefix . $request->filename);
                    }
                        
                    Storage::disk('output')->put($folderPrefix . $request->filename, file_get_contents($request->data));

                    //Backup pdf file to dropbox
                    $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                    $dropbox = new Dropbox($app);
                    $dropboxFile = new DropboxFile(storage_path('/output/') . $companyNumber. '. ' . $project['companyName'] . '/' . $request->filename);
                    $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_OUT') . $filepath. '/' . $request->filename, ['autorename' => TRUE]);
                    return response()->json(["message" => "Success!", "status" => true, "projectId" => $project->id, "directory" => $folderPrefix . $project['clientProjectNumber'] . '. ' . $project['clientProjectName'] . '/']);
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this pdf file.", "status" => false]);
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
            $caseData['RafterDataInput'] = array("B1" => number_format(floatval(isset($caseInput["b-1-{$number}"]) ? $caseInput["b-1-{$number}"] : 0), 2), "B2" => number_format(floatval(isset($caseInput["b-2-{$number}"]) ? $caseInput["b-2-{$number}"] : 0), 2), "B3" => number_format(floatval($caseInput["b-3-{$number}"]), 2), "B4" => $caseInput["b-4-{$number}"]);
            $caseData['CollarTieInformation'] = array(
                "C1" => isset($caseInput["c-1-{$number}"]) ? $caseInput["c-1-{$number}"] : "",
                "C2_feet" => number_format(floatval($caseInput["cf-2-{$number}"]), 2), "C2_inches" => number_format(floatval($caseInput["ci-2-{$number}"]), 2), "C2" => number_format(floatval(isset($caseInput["c-2-{$number}"]) ? $caseInput["c-2-{$number}"] : 0), 2),
                "C3" => number_format(floatval(isset($caseInput["c-3-{$number}"]) ? $caseInput["c-3-{$number}"] : 0), 2),
                "C4_feet" => number_format(floatval($caseInput["cf-4-{$number}"]), 2), "C4_inches" => number_format(floatval($caseInput["ci-4-{$number}"]), 2), "C4" => number_format(floatval(isset($caseInput["c-4-{$number}"]) ? $caseInput["c-4-{$number}"] : 0), 2));
            $caseData['RoofDeckSurface'] = array("D1" => number_format(floatval($caseInput["d-1-{$number}"]), 2), "D2" => $caseInput["d-2-{$number}"], "D3" => $caseInput["d-3-{$number}"]);
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
        return view('general.projectlist')->with('companyList', $companyList)
        ->with('planStatusList', $planStatusList)->with('projectStatusList', $projectStatusList);
    }

    protected $globalStates = array("None", "Saved", "Check Requested", "Reviewed", "Submitted", "Report Prepared", "Plan Requested", "Plan Reviewed", "Link Sent", "Completed");
    protected $globalStatus = array("No action", "Plans uploaded to portal", "Plans reviewed", "Comments issued", "Updated plans uploaded to portal", "Revised comments issued", "Final plans uploaded to portal", "PE sealed plans link sent");
    protected $stateColors = array("danger", "primary", "info", "warning", "primary", "info", "primary", "dark", "secondary", "success");
    protected $statusColors = array("danger", "primary", "info", "warning", "primary", "dark", "secondary", "success");

    /**
     * Return the result of server-side rendering
     *
     * @return JSON
     */
    public function getProjectList(Request $request){
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3)
        {
            $handler = new JobRequest;
            $columns = array( 
                0 =>'id', 
                1 =>'job_request.companyId',
                2 =>'userId',
                3 =>'clientProjectNumber',
                4 =>'clientProjectName',
                5 => 'state',
                6 =>'requestFile',
                7 =>'createdTime',
                8 =>'submittedTime',
                9 =>'projectState',
                10 =>'planStatus',
            );
        }
        else
        {
            //if(Auth::user()->userrole != 3)
            $handler = JobRequest::where('job_request.companyId', Auth::user()->companyid);
            //else
                //$handler = new JobRequest;
            $columns = array( 
                0 =>'userId',
                1 =>'clientProjectNumber',
                2 =>'clientProjectName',
                3 => 'state',
                4 =>'createdTime',
                5 =>'submittedTime',
                6 =>'projectState',
                7 =>'planStatus',
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
            });

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
        // filter chatIcon
        if(!empty($request->input("chat")) && $request->input("chat") != "")
        {
            $handler = $handler->where('job_request.chatIcon', 'LIKE', "%{$request->input("chat")}%");
        }

        // admin filter company name, user, project name, project number, project state, plan status
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3){
            if(!empty($request->input("columns.1.search.value")))
                $handler = $handler->where('company_info.company_name', 'LIKE', "%{$request->input("columns.1.search.value")}%");
            if(!empty($request->input("columns.2.search.value")))
                $handler = $handler->where('users.username', 'LIKE', "%{$request->input('columns.2.search.value')}%");
            if(!empty($request->input("columns.3.search.value")))
                $handler = $handler->where('job_request.clientProjectNumber', 'LIKE', "%{$request->input('columns.3.search.value')}%");
            if(!empty($request->input("columns.4.search.value")))
                $handler = $handler->where('job_request.clientProjectName', 'LIKE', "%{$request->input('columns.4.search.value')}%");
            if(isset($request["columns.5.search.value"]))
                $handler = $handler->where('job_request.state', 'LIKE', "%{$request->input('columns.5.search.value')}%");
            if(isset($request["columns.9.search.value"]))
                $handler = $handler->where('job_request.projectState', 'LIKE', "%{$request->input('columns.9.search.value')}%");
            if(isset($request["columns.10.search.value"]))
                $handler = $handler->where('job_request.planStatus', 'LIKE', "%{$request->input('columns.10.search.value')}%");
        }
        else{ // client filter user, project name, project number
            if(!empty($request->input("columns.0.search.value")))
                $handler = $handler->where('users.username', 'LIKE', "%{$request->input('columns.0.search.value')}%");
            if(!empty($request->input("columns.1.search.value")))
                $handler = $handler->where('job_request.clientProjectNumber', 'LIKE', "%{$request->input('columns.1.search.value')}%");
            if(!empty($request->input("columns.2.search.value")))
                $handler = $handler->where('job_request.clientProjectName', 'LIKE', "%{$request->input('columns.2.search.value')}%");
            if(isset($request["columns.3.search.value"]))
                $handler = $handler->where('job_request.state', 'LIKE', "%{$request->input('columns.3.search.value')}%");
            if(isset($request["columns.6.search.value"]))
                $handler = $handler->where('job_request.projectState', 'LIKE', "%{$request->input('columns.6.search.value')}%");
            if(isset($request["columns.7.search.value"]))
                $handler = $handler->where('job_request.planStatus', 'LIKE', "%{$request->input('columns.7.search.value')}%");
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
            foreach ($jobs as $job)
            {
                $nestedData['id'] = $job->id;
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3)
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
                
                if(Auth::user()->userrole == 2){
                    $nestedData['projectstate'] = "<span class='badge badge-{$this->stateColors[intval($job->projectstate)]} dropdown-toggle job-dropdown' id='state_{$job->id}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> {$this->globalStates[intval($job->projectstate)]} </span>";

                    $nestedData['projectstate'] .= "<div class='dropdown-menu' aria-labelledby='state_{$job->id}'>";
                    $i = 0;
                    foreach($this->globalStates as $state){
                        $nestedData['projectstate'] .= "<a class='dropdown-item' href='javascript:changeState({$job->id}, {$i})'>{$state}</a>";
                        $i ++;
                    }
                    $nestedData['projectstate'] .= "</div>";

                    $nestedData['planstatus'] = "<span class='badge badge-{$this->statusColors[intval($job->planstatus)]} dropdown-toggle job-dropdown' id='status_{$job->id}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> {$this->globalStatus[intval($job->planstatus)]} </span>";

                    $nestedData['planstatus'] .= "<div class='dropdown-menu' aria-labelledby='status_{$job->id}'>";
                    $i = 0;
                    foreach($this->globalStatus as $status){
                        $nestedData['planstatus'] .= "<a class='dropdown-item' href='javascript:changeStatus({$job->id}, {$i})'>{$status}</a>";
                        $i ++;
                    }
                    $nestedData['planstatus'] .= "</div>";
                }
                else{
                    $nestedData['projectstate'] = "<span class='badge badge-{$this->stateColors[intval($job->projectstate)]}' style='white-space: pre-wrap;'> {$this->globalStates[intval($job->projectstate)]} </span>";                
                    $nestedData['planstatus'] = "<span class='badge badge-{$this->statusColors[intval($job->planstatus)]}' style='white-space: pre-wrap;'> {$this->globalStatus[intval($job->planstatus)]} </span>";                
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

                $nestedData['actions'] = "
                <div class='text-center' style='display: flex; align-items: center; justify-content: center;'>
                    <a href='rsinput?projectId={$nestedData['id']}' class='btn btn-primary mr-1' style='padding: 3px 6px;'>
                        <i class='fa fa-pencil-alt'></i>
                    </a>" . 
                    "<a href='jobchat?projectId={$nestedData['id']}' class='mr-2 btn btn-" . $chatbadge . "' style='padding: 3px 6px;'>
                        <i class='fab fa-rocketchat'></i>
                    </a>". 
                    "<input class='mr-1' type='checkbox' onchange='togglePlanCheck({$job['id']})'" . ($job['plancheck'] == 1 ? "checked" : "") . ">" . 
                    "<input class='mr-2' type='checkbox' onchange='toggleAsBuilt({$job['id']})'" . ($job['asbuilt'] == 1 ? "checked" : "") . ">" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='js-swal-confirm btn btn-danger mr-1' onclick='delProject(this,{$nestedData['id']})' style='padding: 3px 6px;'>
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

        //Custom

        if(Auth::user()->userrole != 2){
            $custom_inverters = CustomInverter::where('client_no', Auth::user()->companyid)->get(
                array('mfr as module', 'model as submodule', 'rating as option1', 'favorite', 'crc32')
            );
        } else {
            $custom_inverters = CustomInverter::get(
                array('mfr as module', 'model as submodule', 'rating as option1', 'favorite', 'crc32')
            );
        }
        foreach($custom_inverters as $inverter){
            $inverter['option2'] = 'w';
            $inverter['custom'] = true;
            $pv_inverters[] = $inverter;
        }

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
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->companyid == $project['companyId'])
                {
                    $company = Company::where('id', $project->companyId)->first();
                    $companyNumber = $company ? $company['company_number'] : 0;
                    $folderPrefix = "/" . $companyNumber. '. ' . $project['companyName'] . '/';
                    if( Storage::disk('input')->exists($folderPrefix . $project['requestFile']) )
                        return response()->json(['success' => true, 'data' => Storage::disk('input')->get($folderPrefix . $project['requestFile'])]);
                    else
                        return response()->json(['success' => false, 'message' => "Cannot find the project file."] );
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
        if($request['projectId'])
        {
            $project = JobPermit::where('job_id', '=', $request['projectId'])->get();
            if($project)
            {
                // if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3)
                // {
                    return response()->json(['success' => true, 'data' => $project] );
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
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->companyid == $project['companyId'])
                {
                    $datacheck = DataCheck::leftjoin('structural_notes', "structural_notes.id", "=", "data_check.structural_notes")
                    ->where('jobId', $request['projectId'])->first();

                    if($datacheck)
                        return response()->json(['success' => true, 'data' => $datacheck]);
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
                        $project->save();
                        return response()->json(['success' => true, 'stateText' => "{$this->globalStates[intval($request['state'])]}", 'stateColor' => "{$this->stateColors[intval($request['state'])]}"]);
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
                        return response()->json(['success' => true, 'statusText' => "{$this->globalStatus[intval($request['status'])]}", 'statusColor' => "{$this->statusColors[intval($request['status'])]}"]);
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
                
                $state = '';
                if(Storage::disk('input')->exists($folderPrefix . $job['requestFile']))
                {
                    $jobData = json_decode(Storage::disk('input')->get($folderPrefix . $job['requestFile']), true);
                    $state = $jobData['ProjectInfo']['State'];
                }
                
                $filepath = $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $state;
                $file->move(storage_path('upload') . $filepath, $file->getClientOriginalName());
                $localpath = storage_path('upload') . $filepath . '/' . $file->getClientOriginalName();

                $job->planStatus = 1;
                $job->save();
                    
                // $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                // $dropbox = new Dropbox($app);
                // $dropboxFile = new DropboxFile($localpath);
                // $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $filepath . '/' . $file->getClientOriginalName(), ['autorename' => TRUE]);
                
                return response()->json(['success' => true, 'message' => 'Uploaded Successfully', 'filename' => $file->getClientOriginalName(), 'path' => $filepath]);
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

                $state = '';
                if(Storage::disk('input')->exists($folderPrefix . $job['requestFile']))
                {
                    $jobData = json_decode(Storage::disk('input')->get($folderPrefix . $job['requestFile']), true);
                    $state = $jobData['ProjectInfo']['State'];
                }
                
                $filepath = $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $state;
                    
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

                $state = '';
                if(Storage::disk('input')->exists($folderPrefix . $job['requestFile']))
                {
                    $jobData = json_decode(Storage::disk('input')->get($folderPrefix . $job['requestFile']), true);
                    $state = $jobData['ProjectInfo']['State'];
                }
                
                $filepath = env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $state . '/' . $request->input('filename');
                    
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

                    $state = '';
                    if(Storage::disk('input')->exists($folderPrefix . $job['requestFile']))
                    {
                        $jobData = json_decode(Storage::disk('input')->get($folderPrefix . $job['requestFile']), true);
                        $state = $jobData['ProjectInfo']['State'];
                    }

                    $setting = UserSetting::where('userId', Auth::user()->id)->first();
                    
                    
                    $zip = new ZipArchive();
                    $filename = $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $state . ".zip";

                    if(file_exists(storage_path('download') . '/' . $filename))
                        unlink(storage_path('download') . '/' . $filename);

                    $zip->open(storage_path('download') . '/' . $filename, ZipArchive::CREATE);
                    foreach($request->input('files') as $filepath){
                        try {
                            $file = $dropbox->download($filepath);
                            //$path = str_contains($filepath, env('DROPBOX_PREFIX_IN')) ? substr($filepath, strpos($filepath, env('DROPBOX_PREFIX_IN')) + 1) : substr($filepath, strpos($filepath, env('DROPBOX_PREFIX_OUT')) + 1);
                            $path = str_contains($filepath, env('DROPBOX_PREFIX_IN')) ? env('DROPBOX_PREFIX_IN') . '/' . basename($filepath) : env('DROPBOX_PREFIX_OUT') . '/' . basename($filepath);
                            if($setting)
                                $path = $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $state . $path;
                            $zip->addFromString($path, $file->getContents());
                        }
                        catch (DropboxClientException $e){}
                    }
                    $zip->close();
                    //return response()->download(storage_path('download') . '/' . $filename, null, ['Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0']);
                    return response()->json(['success' => true, 'link' => 'downloadZip?filename=' . $filename, 'name' => $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $state . ".zip"]);
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

                $state = '';
                if(Storage::disk('input')->exists($folderPrefix . $job['requestFile']))
                {
                    $jobData = json_decode(Storage::disk('input')->get($folderPrefix . $job['requestFile']), true);
                    $state = $jobData['ProjectInfo']['State'];
                }
                
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                
                $filepath = env('DROPBOX_PROJECTS_PATH') . env('DROPBOX_PREFIX_IN') . $folderPrefix . $job['clientProjectNumber'] . '. ' . $job['clientProjectName'] . ' ' . $state . '/' ;
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
                $roof_types = ASCERoofTypes::where('asce', $year_value['asce7_in_years'])->get();
                $data = array();
                foreach($roof_types as $type)
                    $data[] = $type->roof_type;
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
                $users = User::where('companyid', Auth::user()->companyid)->get();
                if($request['projectNumber'] > $max + count($users)){
                    $biggerthanmax = true;
                    $maxrange = $max + count($users);
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
     * @return JSON
     */
    public function jobChat(Request $request){
        if(!empty($request['projectId'])){
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $project->companyId == Auth::user()->companyid)
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
     * Add chat message to job_chat table.
     *
     * @return JSON
     */
    public function submitChat(Request $request){
        $project = JobRequest::where('id', '=', $request['projectId'])->first();
        if($project){
            if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $project->companyId == Auth::user()->companyid)
            {
                $chatItem = JobChat::create([
                    'jobId' => $request['projectId'],
                    'userId' => Auth::user()->id,
                    'text' => $request['message']
                ]);
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3)
                    $project->chatIcon = 2;
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
                        //Mailing
                        $data = ['projectName' => $project->clientProjectName, 'projectNumber' => $project->clientProjectNumber, 
                        'projectState' => $project->state];

                        foreach($users as $user){
                            if ($user) {
                                Mail::send('mail.chatnotification', $data, function ($m) use ($user) {
                                    $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($user->email)->subject('Chat is updated on the project');
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
        $data = ['projectName' => $project->clientProjectName, 'projectNumber' => $project->clientProjectNumber, 
        'projectState' => $project->state];

        //Mailing
        foreach($users as $user){
            if ($user) {
                Mail::send('mail.chatnotification', $data, function ($m) use ($user) {
                    $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($user->email)->subject('Chat is deleted on the project');
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
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $project->companyId == Auth::user()->companyid)
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
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $project->companyId == Auth::user()->companyid)
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
     * Check job chat messages count and return updated ones
     *
     * @return JSON
     */
    public function checkChatList(Request $request){
        if(!empty($request['jobId']) && !empty($request['msgCount'])){
            $project = JobRequest::where('id', '=', $request['jobId'])->first();
            if($project){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $project->companyId == Auth::user()->companyid)
                {
                    $messages = JobChat::leftjoin('users', "users.id", "=", "job_chat.userId")
                            ->where('job_chat.jobId', $request['jobId'])
                            ->orderBy('job_chat.id', 'desc')
                            ->get(array('job_chat.id as id', 'users.username as user', 'users.userrole as role', 'job_chat.text as message', 'job_chat.datetime as datetime'))->toArray();
                    $messages = array_slice($messages, 0, count($messages) - $request['msgCount']);
                    return response()->json(["success" => true, "msgCount" => count($messages), "msgs" => $messages]);
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this project.", "success" => false]);
            } else
                return response()->json(["message" => "Cannot find project.", "success" => false]);
        } else 
            return response()->json(["message" => "Wrong Parameters.", "success" => false]);
    }

    /**
     * Check job chat messages count and return updated ones
     *
     * @return JSON
     */
    public function getPermitList(Request $request){
        if(!empty($request['state'])){
            if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->allow_permit == 1){
                $list = PermitFiles::where('state', $request['state'])->get();
                return response()->json(["data" => $list, "success" => true]);
            } else {
                return response()->json(["message" => "You don't have permission to permit tab.", "success" => false]);
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
}
